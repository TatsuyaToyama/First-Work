<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';
    use HasFactory;

    protected $fillable = [
        'name', 
        'date',
        'work_start', 
        'work_end', 
        'break_start', 
        'break_end',
        'break_total'
    ];


    public function scopeNameSearch($query, $name)
    {
    if (!empty($name)) {
        $query->where('name', $name);
    }
    }

    public function scopeDateSearch($query, $date)
    {
    if (!empty($name)) {
        $query->where('date', $date);
    }
    }
}
