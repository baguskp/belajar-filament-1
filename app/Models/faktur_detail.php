<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class faktur_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $table = 'faktur_detail';

    public function faktur()
    {
        return $this->belongsTo(faktur::class, 'faktur_id');
    }

    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
}

