<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['trip_id', 'name', 'budget'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
