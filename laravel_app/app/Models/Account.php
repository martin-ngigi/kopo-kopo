<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public $incrementing = false; // This will prevent  returning "0" when created
    public $timestamps = false; // Set to false to disable timestamps
    protected $primaryKey = 'account_id';
    protected $keyType = 'string';
    
    protected $casts = [
        'account_id' => 'string',
    ];

    protected $fillable = [
        'account_id',
        'balance'
    ];
}
