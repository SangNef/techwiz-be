<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = ['trip_id', 'url'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
