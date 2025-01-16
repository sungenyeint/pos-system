<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $data = [
            ['id' => (string) Str::uuid(), 'name' => 'diaper'],
            ['id' => (string) Str::uuid(), 'name' => 'ဖိနပ်'],
            ['id' => (string) Str::uuid(), 'name' => 'ခေါင်းထုတ်'],
            ['id' => (string) Str::uuid(), 'name' => 'အ၀တ်အစား'],
            ['id' => (string) Str::uuid(), 'name' => 'ဖြည့်စွက်စာ'],
            ['id' => (string) Str::uuid(), 'name' => 'ကစားစရာ'],
            ['id' => (string) Str::uuid(), 'name' => 'cosmetic'],
            ['id' => (string) Str::uuid(), 'name' => 'အသုံးအဆောင်'],
            ['id' => (string) Str::uuid(), 'name' => 'ဆေး'],
            ['id' => (string) Str::uuid(), 'name' => 'စောင်, အိပ်ယာခင်း'],
            ['id' => (string) Str::uuid(), 'name' => 'ခင်းနှီး'],
        ];

        Category::insert($data);
    }
}
