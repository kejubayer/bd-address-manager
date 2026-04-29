<?php

namespace Kejubayer\BdAddress\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'bd_divisions';

    protected $fillable = [
        'name_en',
        'name_bn',
        'slug',
        'code',
        'status'
    ];

    // Relationships
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
