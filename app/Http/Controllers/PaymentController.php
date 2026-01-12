<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\SSLCommerzService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $sslcommerz;

    public function __construct(SSLCommerzService $sslcommerz)
    {
        $this->sslcommerz = $sslcommerz;
    }

    public function pay(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Prevent paying if already paid
        if ($invoice->status == 'paid') {
            return redirect()->back()->with('error', 'Invoice is already paid.');
        }

        $user = auth()->user();

        // Generate a unique transaction ID
        $trnx_id = uniqid();

        // Prepare data for SSLCommerz
        $paymentData = [
            'amount' => $invoice->amount,
            'transaction_id' => $trnx_id,
            'currency' => 'BDT',
            'cus_name' => $user->name,
            'cus_email' => $user->email,
            'cus_phone' => $user->phone ?? '01700000000',
            'cus_add1' => 'Dhaka',
            'invoice_id' => $invoice->id, // Pass invoice ID to service
        ];

        // Initiate Payment
        $response = $this->sslcommerz->initiatePayment($paymentData);

        if ($response['status'] == 'success') {
            return redirect($response['redirect_url']);
        } else {
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $response['message']);
        }
    }

    public function success(Request $request)
    {
        $val_id = $request->input('val_id');
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $invoice_id = $request->input('value_a'); // Retrieve invoice ID

        // Validate Payment via API
        $validation = $this->sslcommerz->validatePayment($val_id);

        if ($validation['status'] == 'VALID' || $validation['status'] == 'VALIDATED') {
            
            DB::beginTransaction();
            try {
                // Check if payment already exists
                $payment = Payment::where('transaction_id', $tran_id)->first();

                if (!$payment) {
                    $payment = Payment::create([
                        'invoice_id' => $invoice_id,
                        'transaction_id' => $tran_id,
                        'amount' => $amount, // Use amount from callback or validation
                        'currency' => 'BDT',
                        'status' => 'Completed',
                        'val_id' => $val_id,
                        'card_type' => $validation['card_type'] ?? null,
                    ]);
                } else {
                    $payment->update([
                        'status' => 'Completed',
                        'val_id' => $val_id,
                        'card_type' => $validation['card_type'] ?? null,
                    ]);
                }

                // Update Invoice
                $invoice = Invoice::findOrFail($invoice_id);
                $invoice->update(['status' => 'paid']);

                DB::commit();

                // Auto-login the user to prevent session drop issues
                if ($invoice->parent) {
                    auth()->login($invoice->parent);
                }

                return redirect()->route('parent.invoice')->with('success', 'Payment successful! Transaction ID: ' . $tran_id);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment Database Error: ' . $e->getMessage());
                return redirect()->route('parent.invoice')->with('error', 'Payment validated but database update failed.');
            }

        } else {
            return redirect()->route('parent.invoice')->with('error', 'Payment validation failed.');
        }
    }

    public function fail(Request $request)
    {
        // Since we don't save pending payments, we just redirect back with error
        return redirect()->route('parent.invoice')->with('error', 'Payment failed.');
    }

    public function cancel(Request $request)
    {
        // Since we don't save pending payments, we just redirect back with error
        return redirect()->route('parent.invoice')->with('error', 'Payment canceled.');
    }

    public function ipn(Request $request)
    {
        // Handle IPN for background updates (Optional for now, but good practice)
        // Similar validation logic as success
        return response()->json(['status' => 'IPN Received']);
    }

    public function downloadReceipt($transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)
            ->with(['invoice.child', 'invoice.parent'])
            ->firstOrFail();

        // Verify ownership (optional but recommended)
        $user = auth()->user();
        if ($user->role !== 'admin' && $payment->invoice->parent_id != $user->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('parent.receipt-pdf', compact('payment'));
        return $pdf->stream('receipt-' . $transactionId . '.pdf');
    }
}
