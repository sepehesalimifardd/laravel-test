<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributesTableSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            ['name' => 'size'],
            ['name' => 'length'],
            ['name' => 'color'],
            ['name' => 'material'],
            ['name' => 'brand']
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
