<?php

$courts = App\Models\Court::with(['facility', 'sportType'])
    ->orderBy('sport_type_id', 'asc')
    ->take(4)
    ->get();

print_r($courts->pluck('id')->toArray());
