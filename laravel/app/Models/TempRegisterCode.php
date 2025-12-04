<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempRegisterCode extends Model
{
    use HasFactory;

    protected $table = 'temp_register_codes';

    protected $fillable = [
        'discord_id',
        'register_code',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}