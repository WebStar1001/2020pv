<?php

use Illuminate\Database\Seeder;

class LanguageSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $items = [
		    ['id' => 1, 'code' => 'en', 'title' => 'English'],
		    ['id' => 2, 'code' => 'fr', 'title' => 'French'],
		    ['id' => 3, 'code' => 'zh', 'title' => 'Chinese'],
		    ['id' => 4, 'code' => 'hi', 'title' => 'Hindi'],
		    ['id' => 5, 'code' => 'mx', 'title' => 'Mexican'],
		    ['id' => 6, 'code' => 'es', 'title' => 'Spanish'],
		    ['id' => 7, 'code' => 'la', 'title' => 'Latin'],
		    ['id' => 8, 'code' => 'pt', 'title' => 'Portuguese'],
	    ];

	    foreach ($items as $item) {
		    \App\Language::updateOrCreate(['id' => $item['id']], $item);
	    }
    }
}
