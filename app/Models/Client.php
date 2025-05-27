<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'contact_person'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'contact_person');
    }
    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
}
