<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function loadRecentlyReviewed(){

        $before = Carbon::now()->subMonth(2)->timestamp;
        $current = Carbon::now()->timestamp;

        $this->recentlyReviewed = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, summary, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date < {$current}
                & rating_count > 5);
                sort total_rating_count desc;
                limit 3;","text/plain"
            )->post(config('services.igdb.endpoint'))->json();  
    }


    public function render()
    {
        return view('livewire.recently-reviewed');
    }
}
