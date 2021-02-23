<?php

use Illuminate\Database\Seeder;

class HoroscopeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'title' => 'Aquarius', 'slug' => 'aquarius'],
	        ['id' => 2, 'title' => 'Pisces', 'slug' => 'pisces'],
	        ['id' => 3, 'title' => 'Aries', 'slug' => 'aries'],
	        ['id' => 4, 'title' => 'Taurus', 'slug' => 'taurus'],
	        ['id' => 5, 'title' => 'Gemini', 'slug' => 'gemini'],
	        ['id' => 6, 'title' => 'Cancer', 'slug' => 'cancer'],
	        ['id' => 7, 'title' => 'Leo', 'slug' => 'leo'],
	        ['id' => 8, 'title' => 'Virgo', 'slug' => 'virgo'],
	        ['id' => 9, 'title' => 'Libra', 'slug' => 'libra'],
	        ['id' => 10, 'title' => 'Scorpio', 'slug' => 'scorpio'],
	        ['id' => 11, 'title' => 'Sagittarius', 'slug' => 'sagittarius'],
	        ['id' => 12, 'title' => 'Capricorn', 'slug' => 'capricorn'],
        ];

        foreach ($items as $item) {
            \App\Horoscope::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
