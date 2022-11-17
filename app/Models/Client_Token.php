<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_Token extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'token'];
}
