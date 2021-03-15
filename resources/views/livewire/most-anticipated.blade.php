<div wire:init="loadMostAnticipated" class="most-anticipated-container space-y-10 mt-8">
    @forelse($mostAnticipated as $game)
        <div class="game flex">
            <a href="{{ route('games.show', $game['slug'])}}">
                @if(isset($game['cover']['url']))
                    <img src="{{ Str::replaceFirst('thumb','cover_small', $game['cover']['url']) }}" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
                @else
                    <img src="cyberpunk.jpg" alt="game cover" style="width: 90px;height: 120px;"
                        class="hover:opacity-75 transition ease-in-out duration-150">
                @endif
            </a>
            <div class="ml-4">
                <a href="{{ route('games.show', $game['slug'])}}" class="hover:text-gray-300">{{ $game['name'] }}</a>
                <div class="text-gray-400 text-sm mt-1">{{ Carbon\Carbon::parse($game['first_release_date'])->format('M d, Y') }}</div>
            </div>
        </div>
    @empty 
        @foreach(range(1, 4) as $game)
            <div class="game flex">
                <div class="bg-gray-800 w-16 h-20 flex-none"></div>
                <div class="ml-4">
                    <div class="block text-transparent text-lg bg-gray-700 rounded leading-tight  mt-4">Title goes here</div>
                    <div class="inline-block text-transparent text-lg bg-gray-700 rounded leading-tight  mt-2">Aug 01, 2021</div>
                </div>
            </div>
        @endforeach
    @endforelse
</div>
