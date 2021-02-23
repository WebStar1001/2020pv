<?php

use Illuminate\Database\Seeder;

class EducationSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'title' => 'Breakups/Divorce'],
	        ['id' => 2, 'title' => 'Destiny/Life path'],
	        ['id' => 3, 'title' => 'Money/Prosperity'],
	        ['id' => 4, 'title' => 'Depression/Anxiety'],
        ];

        foreach ($items as $item) {
            \App\Education::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
