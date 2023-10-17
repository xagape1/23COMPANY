<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies')->insert([
            [
                'title' => 'The Shawshank Redemption',
                'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'gender' => 'Drama',
                'created_at' => now(),
                'updated_at' => now(),
                'cover_id' => 1,
                'intro_id' => 1
            ],
            [
                'title' => 'The Godfather',
                'description' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
                'gender' => 'Crime',
                'created_at' => now(),
                'updated_at' => now(),
                'cover_id' => 2,
                'intro_id' => 2
            ],
            [
                'title' => 'The Dark Knight',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                'gender' => 'Action',
                'created_at' => now(),
                'updated_at' => now(),
                'cover_id' => 3,
                'intro_id' => 3
            ]
        ]);
    }
}
