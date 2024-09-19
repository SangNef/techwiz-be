<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    
    protected $table = 'currencies';
    protected $fillable = ['name', 'code', 'exchange_rate', 'is_default'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
