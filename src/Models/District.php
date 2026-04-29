<?php

namespace Kejubayer\BdAddress\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'bd_districts';

    protected $fillable = [
        'division_id',
        'name_en',
        'name_bn',
        'slug',
        'code',
        'status'
    ];

    // Relationships
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
