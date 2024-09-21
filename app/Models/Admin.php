<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = ['email', 'password', 'role','deleted_at'];
}
