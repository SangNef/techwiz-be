<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'destination_id',
        'package_id',
        'name',
        'start_date',
        'end_date',
        'budget',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    /**
     * Get the user associated with the trip.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination associated with the trip.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the package associated with the trip.
     */
    public function package()
    {
        return $this->belongsTo(TripPackage::class, 'package_id');
    }
}
