<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    protected $primaryKey = 'email';
    protected $table = 'password_reset_tokens'; 
}
