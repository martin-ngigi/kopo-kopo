<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false; // This will prevent  returning "0" when created
    public $timestamps = false; // Set to false to disable timestamps
    protected $primaryKey = 'transaction_id';
    protected $keyType = 'string';
    
    protected $casts = [
        'transaction_id' => 'string',
    ];


    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount'
    ];
}
