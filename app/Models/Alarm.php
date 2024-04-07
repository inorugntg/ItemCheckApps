<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    use HasFactory;

    protected $fillable=[
        'nama',
        'kode',
        'lokasi',
        'supplier',
        'status',
        'media',
        'user_id'
    ];

    public function User(){
        $this->belongsTo(User::class);
    }
}
