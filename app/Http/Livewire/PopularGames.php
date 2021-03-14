<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames(){

        $before = Carbon::now()->subMonth(2)->timestamp;
        $after = Carbon::now()->addMonth(2)->timestamp;

        $this->popularGames = Cache::remember('popular-games', 7, function ()use ($before, $after) {
        //   sleep(3);
          return   Http::withHeaders(
                config('services.igdb.headers')
                )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$after});
                    sort total_rating_count desc;
                    limit 12;","text/plain"
                )->post(config('services.igdb.endpoint'))->json();
        });
    }

    public function render()
    {
        return view('livewire.popular-games');
    }
}
