<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // ↓↓↓ TAMBAHKAN BARIS INI ↓↓↓
    protected $guarded = ['id'];
    
    protected $fillable = [
        'invoice_number',
        'user_id', 
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method'
    ];

    // Tambahkan relasi ini untuk nanti
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}