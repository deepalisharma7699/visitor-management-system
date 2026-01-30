<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'designation',
        'is_active',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get visitors assigned to this employee
     */
    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'whom_to_meet');
    }

    /**
     * Scope to get active employees
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get employees by department
     */
    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }
}
