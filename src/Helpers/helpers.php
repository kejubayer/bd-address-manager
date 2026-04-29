<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Kejubayer\BdAddress\Models\Division;
use Kejubayer\BdAddress\Models\District;
use Kejubayer\BdAddress\Models\Upazila;
use Kejubayer\BdAddress\Models\Union;

/*
|--------------------------------------------------------------------------
| DIVISIONS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_divisions')) {
    function bd_divisions($cache = true)
    {
        if ($cache) {
            return Cache::remember('bd_divisions', 86400, function () {
                return Division::active()->get();
            });
        }

        return Division::active()->get();
    }
}

/*
|--------------------------------------------------------------------------
| DISTRICTS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_districts')) {
    function bd_districts($divisionId = null)
    {
        return District::when($divisionId, function ($q) use ($divisionId) {
            $q->where('division_id', $divisionId);
        })->active()->get();
    }
}

/*
|--------------------------------------------------------------------------
| UPAZILAS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_upazilas')) {
    function bd_upazilas($districtId = null)
    {
        return Upazila::when($districtId, function ($q) use ($districtId) {
            $q->where('district_id', $districtId);
        })->active()->get();
    }
}

/*
|--------------------------------------------------------------------------
| UNIONS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_unions')) {
    function bd_unions($upazilaId = null)
    {
        return Union::when($upazilaId, function ($q) use ($upazilaId) {
            $q->where('upazila_id', $upazilaId);
        })->active()->get();
    }
}

/*
|--------------------------------------------------------------------------
| FIND BY SLUG (UPDATED)
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_find_by_slug')) {
    function bd_find_by_slug($type, $slug)
    {
        $map = [
            'division' => Division::class,
            'district' => District::class,
            'upazila'  => Upazila::class,
            'union'    => Union::class,
        ];

        if (!isset($map[$type])) {
            return null;
        }

        return $map[$type]::where('slug', $slug)->first();
    }
}

/*
|--------------------------------------------------------------------------
| FULL ADDRESS BUILDER (EN)
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_full_address')) {
    function bd_full_address($unionId)
    {
        $union = Union::with('upazila.district.division')->find($unionId);

        if (!$union) return null;

        return trim(implode(', ', [
            $union->name_en ?? null,
            $union->upazila->name_en ?? null,
            $union->upazila->district->name_en ?? null,
            $union->upazila->district->division->name_en ?? null,
        ]));
    }
}

/*
|--------------------------------------------------------------------------
| FULL ADDRESS (BANGLA)
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_full_address_bn')) {
    function bd_full_address_bn($unionId)
    {
        $union = Union::with('upazila.district.division')->find($unionId);

        if (!$union) return null;

        return trim(implode(', ', [
            $union->name_bn ?? null,
            $union->upazila->name_bn ?? null,
            $union->upazila->district->name_bn ?? null,
            $union->upazila->district->division->name_bn ?? null,
        ]));
    }
}

/*
|--------------------------------------------------------------------------
| SEARCH ALL LEVELS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_search')) {
    function bd_search($keyword)
    {
        return [
            'divisions' => Division::where('name_en', 'like', "%$keyword%")
                ->orWhere('name_bn', 'like', "%$keyword%")
                ->get(),

            'districts' => District::where('name_en', 'like', "%$keyword%")
                ->orWhere('name_bn', 'like', "%$keyword%")
                ->get(),

            'upazilas' => Upazila::where('name_en', 'like', "%$keyword%")
                ->orWhere('name_bn', 'like', "%$keyword%")
                ->get(),

            'unions' => Union::where('name_en', 'like', "%$keyword%")
                ->orWhere('name_bn', 'like', "%$keyword%")
                ->get(),
        ];
    }
}

/*
|--------------------------------------------------------------------------
| SLUG HELPER
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_slug')) {
    function bd_slug($text)
    {
        return Str::slug($text);
    }
}

/*
|--------------------------------------------------------------------------
| DROPDOWN HELPERS
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_division_dropdown')) {
    function bd_division_dropdown()
    {
        return Division::active()->pluck('name_en', 'id');
    }
}

if (!function_exists('bd_district_dropdown')) {
    function bd_district_dropdown($divisionId)
    {
        return District::where('division_id', $divisionId)
            ->active()
            ->pluck('name_en', 'id');
    }
}

if (!function_exists('bd_upazila_dropdown')) {
    function bd_upazila_dropdown($districtId)
    {
        return Upazila::where('district_id', $districtId)
            ->active()
            ->pluck('name_en', 'id');
    }
}

if (!function_exists('bd_union_dropdown')) {
    function bd_union_dropdown($upazilaId)
    {
        return Union::where('upazila_id', $upazilaId)
            ->active()
            ->pluck('name_en', 'id');
    }
}

/*
|--------------------------------------------------------------------------
| BREADCRUMB (UPDATED)
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_breadcrumb')) {
    function bd_breadcrumb($unionId)
    {
        $union = Union::with('upazila.district.division')->find($unionId);

        if (!$union) return [];

        return [
            'division' => $union->upazila->district->division->name_en ?? null,
            'district' => $union->upazila->district->name_en ?? null,
            'upazila'  => $union->upazila->name_en ?? null,
            'union'    => $union->name_en ?? null,
        ];
    }
}

/*
|--------------------------------------------------------------------------
| CACHE CLEAR HELPER
|--------------------------------------------------------------------------
*/

if (!function_exists('bd_clear_cache')) {
    function bd_clear_cache()
    {
        Cache::forget('bd_divisions');
        return true;
    }
}
