<?php

namespace Kejubayer\BdAddress\Models;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $table = 'bd_unions';

    protected $fillable = [
        'upazila_id',
        'name_en',
        'name_bn',
        'slug',
        'code',
        'status'
    ];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
