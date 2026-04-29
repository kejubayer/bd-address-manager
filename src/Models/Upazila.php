<?php

namespace Kejubayer\BdAddress\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $table = 'bd_upazilas';

    protected $fillable = [
        'district_id',
        'name_en',
        'name_bn',
        'slug',
        'code',
        'status'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function unions()
    {
        return $this->hasMany(Union::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
