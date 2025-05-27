<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    /** @use HasFactory<\Database\Factories\TimeLogFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id', 
        'start_time', 
        'end_time', 
        'description'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
