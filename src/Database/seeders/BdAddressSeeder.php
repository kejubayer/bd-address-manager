<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BdAddressSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {

            $json = file_get_contents(database_path('bd_address.json'));
            $data = json_decode($json, true);

            foreach ($data as $division) {

                // Division
                $divisionId = DB::table('bd_divisions')->insertGetId([
                    'name_en' => $division['name_en'],
                    'name_bn' => $division['name_bn'],
                    'slug'    => $this->makeSlug('bd_divisions', $division['name_en']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($division['districts'] ?? [] as $district) {

                    // District
                    $districtId = DB::table('bd_districts')->insertGetId([
                        'division_id' => $divisionId,
                        'name_en'     => $district['name_en'],
                        'name_bn'     => $district['name_bn'],
                        'slug'        => $this->makeSlug('bd_districts', $district['name_en'], 'division_id', $divisionId),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);

                    foreach ($district['upazilas'] ?? [] as $upazila) {

                        // Upazila
                        $upazilaId = DB::table('bd_upazilas')->insertGetId([
                            'district_id' => $districtId,
                            'name_en'     => $upazila['name_en'],
                            'name_bn'     => $upazila['name_bn'],
                            'slug'        => $this->makeSlug('bd_upazilas', $upazila['name_en'], 'district_id', $districtId),
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]);

                        foreach ($upazila['unions'] ?? [] as $union) {

                            // Union
                            $unionId = DB::table('bd_unions')->insertGetId([
                                'upazila_id' => $upazilaId,
                                'name_en'    => $union['name_en'],
                                'name_bn'    => $union['name_bn'],
                                'slug'       => $this->makeSlug('bd_unions', $union['name_en'], 'upazila_id', $upazilaId),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate unique slug (with optional parent scope)
     */
    private function makeSlug($table, $name, $parentColumn = null, $parentId = null)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            DB::table($table)
                ->when($parentColumn, function ($q) use ($parentColumn, $parentId) {
                    $q->where($parentColumn, $parentId);
                })
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
