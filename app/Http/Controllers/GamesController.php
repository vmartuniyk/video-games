<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $before = Carbon::now()->subMonth(2)->timestamp;
        $after = Carbon::now()->addMonth(2)->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;
        $current = Carbon::now()->timestamp;

        // $game = Http::withHeaders(config('services.igdb.headers'))
        //     ->withBody("fields name; limit 10;", "text/plain")->post('https://api.igdb.com/v4/games')
        //     ->json();
            
        $popularGames = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date < {$after});
                sort total_rating_count desc;
                limit 12;","text/plain"
            )->post(config('services.igdb.endpoint'))->json();

        $recentlyReviewed = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, summary, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date < {$current}
                & rating_count > 5);
                sort total_rating_count desc;
                limit 3;","text/plain"
            )->post(config('services.igdb.endpoint'))->json();    

        $mostAnticipated = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, summary, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$current}
                & first_release_date < {$afterFourMonths});
                sort total_rating_count desc;
                limit 4;","text/plain"
            )->post(config('services.igdb.endpoint'))->json(); 

        $comingSoon = Http::withHeaders(
            config('services.igdb.headers')
            )->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, summary, slug;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$current});
                sort first_release_date asc;
                limit 4;","text/plain"
            )->post(config('services.igdb.endpoint'))->json(); 
        // dd($comingSoon);
        

        return view('index',[
            'popularGames' => $popularGames,
            'mostAnticipated' => $mostAnticipated,
            'recentlyReviewed' => $recentlyReviewed,
            'comingSoon' => $comingSoon,
        ]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
