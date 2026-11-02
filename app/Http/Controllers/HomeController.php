<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Population;
use App\Models\Town;
use Illuminate\View\View;

/**
 * TASK 4 - Mainpage: introduces the application with live figures from the database.
 */
class HomeController extends Controller
{
    public function index(): View
    {
        $latestYear = Population::max('ryear');
        $firstYear  = Population::min('ryear');

        $stats = [
            'counties'    => County::count(),
            'towns'       => Town::count(),
            'countySeats' => Town::where('countyseat', true)->count(),
            'population'  => $latestYear ? (int) Population::where('ryear', $latestYear)->sum('total') : 0,
        ];

        // The 6 largest towns in the latest year (with their county)
        $largest = Population::with('town.county')
            ->where('ryear', $latestYear)
            ->whereNotNull('total')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // County seats for the "Explore the counties" section
        $countySeats = Town::with('county')
            ->where('countyseat', true)
            ->orderBy('tname')
            ->get();

        return view('home', compact('stats', 'largest', 'countySeats', 'latestYear', 'firstYear'));
    }
}
