<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleSchedule extends Model
{
    use HasFactory;

    protected $table = 'sample_schedules';

    protected $fillable = ['sample_category_id', 'day', 'time', 'activity'];

    public function sampleCategory()
    {
        return $this->belongsTo(SampleCategory::class);
    }
}
