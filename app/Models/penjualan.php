<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penjualan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'penjualans';

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }

    public function faktur()
    {
        return $this->belongsTo(faktur::class, 'faktur_id');
    }
}
