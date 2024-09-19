<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCategory extends Model
{
    use HasFactory;

    protected $table = 'sample_categories';

    protected $fillable = ['sample_id', 'name', 'budget'];

    public function schedules()
    {
        return $this->hasMany(SampleSchedule::class);
    }

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
