<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'card_type',
        'val_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
