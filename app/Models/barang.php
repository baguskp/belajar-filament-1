<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class barang extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function faktur_detail()
    {
        return $this->hasMany(faktur_detail::class, 'barang_id');
    }
}
