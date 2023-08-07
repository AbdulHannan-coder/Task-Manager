<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['id','name', 'priority', 'timestamps'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
