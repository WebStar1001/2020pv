<?php

use Illuminate\Database\Seeder;

class ServiceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $items = [
		    ['id' => 13, 'title' => 'Tarot Readings', 'slug' => 'tarot-Readings', 'master_service' => 1],
		    ['id' => 9, 'title' => 'Love & Relationships', 'slug' => 'love-and-relationships', 'master_service' => 1],
		    ['id' => 12, 'title' => 'Psychic Readings', 'slug' => 'psychic-readings', 'master_service' => 1],
		    ['id' => 20, 'title' => 'Astrology Readings', 'slug' => 'astrology-readings', 'master_service' => 1],
		    ['id' => 1, 'title' => 'Western astrology', 'slug' => 'western-astrology'],
		    ['id' => 2, 'title' => 'Indian astrology', 'slug' => 'indian-astrology'],
		    ['id' => 3, 'title' => 'Chinese astrology', 'slug' => 'chinese-astrology'],
		    ['id' => 4, 'title' => 'Career Advice', 'slug' => 'career-advice'],
		    ['id' => 5, 'title' => 'Clairsentience', 'slug' => 'clairsentience'],
		    ['id' => 6, 'title' => 'Clairaudien', 'slug' => 'clairaudien'],
		    ['id' => 7, 'title' => 'Claircognizance', 'slug' => 'claircognizance'],
		    ['id' => 8, 'title' => 'Dream Analysis', 'slug' => 'dream-analysis'],
		    ['id' => 10, 'title' => 'Romance and intimacy', 'slug' => 'romance-and-intimacy'],
		    ['id' => 11, 'title' => 'Soulmates', 'slug' => 'soulmates'],
		    ['id' => 14, 'title' => 'Gay & Lesbian Friendly', 'slug' => 'gay-and-lesbian-friendly'],
		    ['id' => 15, 'title' => 'Marital Life', 'slug' => 'marital-life'],
		    ['id' => 16, 'title' => 'Parents & Children', 'slug' => 'parents-and-children'],
		    ['id' => 17, 'title' => 'Single & Dating', 'slug' => 'single-and-dating'],
		    ['id' => 18, 'title' => 'Soulmate Connections', 'slug' => 'soulmate-connections'],
		    ['id' => 19, 'title' => 'Angel Card Reading', 'slug' => 'angel-card-reading'],
		    ['id' => 78, 'title' => 'Cartomancy', 'slug' => 'cartomancy'],
		    ['id' => 21, 'title' => 'Vedic Astrology', 'slug' => 'vedic-astrology'],
		    ['id' => 22, 'title' => 'Religion', 'slug' => 'religion'],
		    ['id' => 23, 'title' => 'Christianity', 'slug' => 'christianity'],
		    ['id' => 24, 'title' => 'Islam', 'slug' => 'islam'],
		    ['id' => 25, 'title' => 'Online Confessions', 'slug' => 'online-confessions'],
		    ['id' => 27, 'title' => 'Palm Readings', 'slug' => 'palm-readings'],
		    ['id' => 28, 'title' => 'Paranormal', 'slug' => 'paranormal'],
		    ['id' => 29, 'title' => 'Extra Sensory Perception (ESP)', 'slug' => 'extra-sensory-perception'],
		    ['id' => 30, 'title' => 'Ghosts', 'slug' => 'ghosts'],
		    ['id' => 31, 'title' => 'Precognition', 'slug' => 'precognition'],
		    ['id' => 32, 'title' => 'Universal Laws', 'slug' => 'universal-laws'],
		    ['id' => 33, 'title' => 'Best Psychics', 'slug' => 'best-psychics'],
		    ['id' => 34, 'title' => 'Law of Action', 'slug' => 'law-of-action'],
		    ['id' => 35, 'title' => 'Law of Attraction', 'slug' => 'law-of-attraction'],
		    ['id' => 36, 'title' => 'Law of Cause and Effect', 'slug' => 'law-of-aause-and-effect'],
		    ['id' => 37, 'title' => 'Law of Correspondence', 'slug' => 'law-of-correspondence'],
		    ['id' => 38, 'title' => 'Law of Divine Oneness', 'slug' => 'law-of-divine-oneness'],
		    ['id' => 39, 'title' => 'Law of Gender', 'slug' => 'law-of-gender'],
		    ['id' => 40, 'title' => 'Law of Polarity', 'slug' => 'law-of-polarity'],
		    ['id' => 41, 'title' => 'Law of Relativity', 'slug' => 'law-of-relativity'],
		    ['id' => 42, 'title' => 'Law of Rhythm', 'slug' => 'law-of-rhythm'],
		    ['id' => 43, 'title' => 'Law of Vibration', 'slug' => 'law-of-vibration'],
		    ['id' => 49, 'title' => 'Gnosticism', 'slug' => 'gnosticism'],
		    ['id' => 50, 'title' => 'Hermeticism', 'slug' => 'hermeticism'],
		    ['id' => 51, 'title' => 'Neopaganism', 'slug' => 'neopaganism'],
		    ['id' => 52, 'title' => 'Paganism', 'slug' => 'paganism'],
		    ['id' => 53, 'title' => 'Spell Casting', 'slug' => 'spell-casting'],
		    ['id' => 54, 'title' => 'Thelema', 'slug' => 'thelema'],
		    ['id' => 55, 'title' => 'Eastern Philosophy', 'slug' => 'eastern-philosophy'],
		    ['id' => 56, 'title' => 'Feng Shui', 'slug' => 'feng-shui'],
		    ['id' => 57, 'title' => 'I-Ching', 'slug' => 'i-ching'],
		    ['id' => 58, 'title' => 'Meditation', 'slug' => 'meditation'],
		    ['id' => 59, 'title' => 'Past Life Readings', 'slug' => 'past-life-readings'],
		    ['id' => 60, 'title' => 'Intimacy', 'slug' => 'intimacy'],
		    ['id' => 61, 'title' => 'Fertility', 'slug' => 'fertility'],
		    ['id' => 62, 'title' => 'Kama Sutra', 'slug' => 'kama-sutra'],
		    ['id' => 63, 'title' => 'Tantra', 'slug' => 'tantra'],
		    ['id' => 64, 'title' => 'Graphology', 'slug' => 'graphology'],
		    ['id' => 65, 'title' => 'Picture Reading', 'slug' => 'picture-reading'],
		    ['id' => 66, 'title' => 'Financial Outlook', 'slug' => 'financial-outlook'],
		    ['id' => 68, 'title' => 'New Age Spirituality', 'slug' => 'new-age-spirituality'],
		    ['id' => 69, 'title' => 'Astral Projection', 'slug' => 'astral-projection'],
		    ['id' => 70, 'title' => 'Crystal Healing', 'slug' => 'crystal-healing'],
		    ['id' => 71, 'title' => 'Empowerment', 'slug' => 'empowerment'],
		    ['id' => 72, 'title' => 'Intuitive Behavior', 'slug' => 'intuitive-behavior'],
		    ['id' => 73, 'title' => 'Optimism', 'slug' => 'optimism'],
		    ['id' => 74, 'title' => 'Quantum Healing', 'slug' => 'quantum-healing'],
		    ['id' => 75, 'title' => 'Simple Living', 'slug' => 'simple-living'],
		    ['id' => 76, 'title' => 'Teleology', 'slug' => 'teleology'],
		    ['id' => 77, 'title' => 'The Secret', 'slug' => 'the-secret'],
		    ['id' => 79, 'title' => 'remote viewing', 'slug' => 'remote-viewing'],
		    ['id' => 80, 'title' => 'rune casting', 'slug' => 'rune-casting'],
		    ['id' => 81, 'title' => 'UK psychics', 'slug' => 'uk-psychics'],
		    ['id' => 82, 'title' => 'pet psychics', 'slug' => 'pet-psychics'],
		    ['id' => 83, 'title' => 'psychic mediums', 'slug' => 'psychic-mediums'],
		    ['id' => 84, 'title' => 'aura reading', 'slug' => 'aura-reading'],
		    ['id' => 85, 'title' => 'breakup divorce', 'slug' => 'breakup-divorce'],
		    ['id' => 86, 'title' => 'cheating affairs', 'slug' => 'cheating-affairs'],
		    ['id' => 87, 'title' => 'Fortune telling', 'slug' => 'fortune-telling'],
		    ['id' => 88, 'title' => 'Occult', 'slug' => 'occult'],
		    ['id' => 89, 'title' => 'Numberology', 'slug' => 'numberology'],
		    ['id' => 90, 'title' => 'Kabbalah', 'slug' => 'kabbalah']
	    ];

	    foreach ($items as $item) {
		    \App\Service::updateOrCreate(['id' => $item['id']], $item);
	    }
    }
}