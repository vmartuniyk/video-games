<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames(){

        $before = Carbon::now()->subMonth(4)->timestamp;
        $after = Carbon::now()->addMonth(4)->timestamp;

        $popularGamesUnformatted = Cache::remember('popular-games', 7, function ()use ($before, $after) {
        //   sleep(3);
          return   Http::withHeaders(
                config('services.igdb.headers')
                )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$after}
                    & total_rating_count > 5);
                    sort total_rating_count desc;
                    limit 12;","text/plain"
                )->post(config('services.igdb.endpoint'))->json();
        });
        // dump($this->formatForView($popularGamesUnformatted));
      

        $this->popularGames = $this->formatForView($popularGamesUnformatted);
        collect($this->popularGames)->filter(function ($game){
            return $game['rating'];
        })->each(function ($game){
            $this->emit('gameWithRatingAdded',[
                'slug' => $game['slug'],
                'rating' => $game['rating'] / 100

            ]);

        });

        
    }

    public function render()
    {
        return view('livewire.popular-games');
    }

    private function formatForView($games){
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover']['url']) ? Str::replaceFirst('thumb','cover_big', $game['cover']['url']) : 'cyberpunk_big.jpg',
                'rating' => isset($game['rating']) ? round($game['rating']) : null,
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            ]);
        })->toArray();
    }
}
