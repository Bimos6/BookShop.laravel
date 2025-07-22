<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Author::factory(5)->create();
        
        \App\Models\Book::factory(20)->create([
            'author_id' => fn() => \App\Models\Author::inRandomOrder()->first()->id
        ]);
    }
}
