<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = ['category_id', 'title', 'day', 'hour', 'amount', 'expense_date', 'note'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
