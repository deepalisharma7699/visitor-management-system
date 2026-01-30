<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get visitors interested in this project
     */
    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'interested_project');
    }

    /**
     * Scope to get active projects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
