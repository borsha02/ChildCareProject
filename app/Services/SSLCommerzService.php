<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SSLCommerzService
{
    // ... existing properties ...

    // ... existing constructor ...


    private $storeId;
    private $storePassword;
    private $apiUrl;
    private $sandbox;

    public function __construct()
    {
        $this->storeId = env('SSLC_STORE_ID');
        $this->storePassword = env('SSLC_STORE_PASSWORD');
        $this->sandbox = env('SSLC_SANDBOX_MODE', true); // Default to true if not set

        // Adjust API URL based on sandbox (or checking if store ID contains 'test' or similar if needed, but explicit env is better)
        // If the user's store ID starts with "test" or "sourc" usually it's sandbox, but let's stick to the boolean.
        // The user didn't show SSLC_SANDBOX, but SSLC_ALLOW_LOCALHOST=true.
        // Let's assume sandbox for now or try to detect.
        
        $this->apiUrl = $this->sandbox
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
    }

    public function initiatePayment($data)
    {
        // Basic parameters required by SSLCommerz
        $post_data = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'BDT',
            'tran_id' => $data['transaction_id'],
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.fail'),
            'cancel_url' => route('payment.cancel'),
            'ipn_url' => route('payment.ipn'),
            
            # Pass-through values
            'value_a' => $data['invoice_id'] ?? '',
            
            # Customer Info
            'cus_name' => $data['cus_name'],
            'cus_email' => $data['cus_email'],
            'cus_add1' => $data['cus_add1'] ?? 'Dhaka',
            'cus_add2' => $data['cus_add2'] ?? '',
            'cus_city' => $data['cus_city'] ?? 'Dhaka',
            'cus_state' => $data['cus_state'] ?? 'Dhaka',
            'cus_postcode' => $data['cus_postcode'] ?? '1000',
            'cus_country' => $data['cus_country'] ?? 'Bangladesh',
            'cus_phone' => $data['cus_phone'],
            'cus_fax' => '',
            
            # Shipment Info (Optional)
            'shipping_method' => 'NO',
            'num_of_item' => 1,
            'product_name' => 'Childcare Invoice',
            'product_category' => 'Service',
            'product_profile' => 'general',
        ];

        // Send Request to SSLCommerz
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->apiUrl);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // For Sandbox
        
        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        
        Log::info('SSLCommerz Response Code: ' . $code);
        Log::info('SSLCommerz Response Content: ' . $content);
        
        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = json_decode($content, true);

            if (isset($sslcommerzResponse['status']) && $sslcommerzResponse['status'] == 'SUCCESS') {
                return [
                    'status' => 'success',
                    'redirect_url' => $sslcommerzResponse['GatewayPageURL']
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => $sslcommerzResponse['failedreason'] ?? 'Unknown Error'
                ];
            }
        } else {
            $error = curl_error($handle);
            curl_close($handle);
            Log::error('cURL Error: ' . $error);
            return [
                'status' => 'error',
                'message' => 'Failed to connect to SSLCommerz: ' . $error
            ];
        }
    }

    public function validatePayment($val_id) 
    {
        $validationUrl = $this->sandbox
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
            
        $validationUrl .= "?val_id=" . urlencode($val_id) . "&store_id=" . urlencode($this->storeId) . "&store_passwd=" . urlencode($this->storePassword) . "&v=1&format=json";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $validationUrl);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        
        $content = curl_exec($handle);
        curl_close($handle);
        
        return json_decode($content, true);
    }
}
