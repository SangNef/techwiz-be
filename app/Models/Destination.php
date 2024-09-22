<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';

    protected $fillable = ['name', 'description', 'deleted_at'];

    public function images()
    {
        return $this->hasMany(DestinationImage::class);
    }
        public function trips()
    {
        return $this->hasMany(Trip::class);
    }

}
