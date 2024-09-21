<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';

    protected $fillable = ['user_id', 'destination_id', 'name', 'start_date', 'end_date', 'budget', 'is_completed', 'is_public'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
