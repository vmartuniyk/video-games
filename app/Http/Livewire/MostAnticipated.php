<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    public function loadMostAnticipated() {

        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;
        $current = Carbon::now()->timestamp;

        $mostAnticipatedUnformatted = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, summary, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$current}
                & first_release_date < {$afterFourMonths});
                sort total_rating_count desc;
                limit 4;","text/plain"
            )->post(config('services.igdb.endpoint'))->json();
        
        $this->mostAnticipated = $this->formatForView($mostAnticipatedUnformatted);
    }

    public function render()
    {
        return view('livewire.most-anticipated');
    }

    private function formatForView($games){
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => isset($game['cover']['url']) ? Str::replaceFirst('thumb','cover_small', $game['cover']['url']) : 'images/cyberpunk_small.jpg',
                'releaseDate' => Carbon::parse($game['first_release_date'])->format('M d, Y'),
            ]);
        })->toArray();
    }
}
