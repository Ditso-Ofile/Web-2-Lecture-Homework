<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Population;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * TASK 5 - Database menu.
 * Displays data from 3 tables (counties, towns, populations) using Eloquent ORM only.
 */
class DatabaseController extends Controller
{
    // GET /database  - towns list with filters + counties overview
    public function index(Request $request): View
    {
        // All census years in the populations table, newest first (e.g. 2019, 2018, ...)
        $years = Population::query()
            ->select('ryear')
            ->distinct()
            ->orderByDesc('ryear')
            ->pluck('ryear');

        $year = (int) $request->input('year', $years->first());
        if (! $years->contains($year)) {
            $year = (int) $years->first();
        }

        // Filters coming from the form (all optional)
        $countyId = $request->integer('county') ?: null;
        $search   = trim((string) $request->input('q', ''));
        $seatOnly = $request->boolean('seat');
        $sort     = $request->input('sort') === 'population' ? 'population' : 'name';

        // Towns (table 2) + their county (table 1) + population of the selected year (table 3)
        $towns = Town::query()
            ->with('county')
            ->withSum(['populations as pop_total' => fn ($q) => $q->where('ryear', $year)], 'total')
            ->withSum(['populations as pop_women' => fn ($q) => $q->where('ryear', $year)], 'women')
            ->when($countyId, fn ($q) => $q->where('countyid', $countyId))
            ->when($search !== '', fn ($q) => $q->where('tname', 'like', '%' . $search . '%'))
            ->when($seatOnly, fn ($q) => $q->where('countyseat', true))
            ->when(
                $sort === 'population',
                fn ($q) => $q->orderByDesc('pop_total'),
                fn ($q) => $q->orderBy('tname')
            )
            ->paginate(20)
            ->withQueryString();

        // Counties overview: number of towns and total population in the selected year
        $countySummary = County::query()
            ->withCount('towns')
            ->withSum(['populations as population_sum' => fn ($q) => $q->where('ryear', $year)], 'total')
            ->orderByDesc('population_sum')
            ->get();

        return view('database.index', [
            'towns'         => $towns,
            'counties'      => County::orderBy('cname')->get(),
            'countySummary' => $countySummary,
            'years'         => $years,
            'year'          => $year,
            'countyId'      => $countyId,
            'search'        => $search,
            'seatOnly'      => $seatOnly,
            'sort'          => $sort,
        ]);
    }

    // GET /database/towns/{town}  - one town with its full population history
    public function show(Town $town): View
    {
        $town->load([
            'county',
            'populations' => fn ($q) => $q->orderBy('ryear'),
        ]);

        $first = $town->populations->first();
        $last  = $town->populations->last();

        $change = null;
        if ($first && $last && $first->total && $last->total !== null) {
            $change = round(($last->total - $first->total) / $first->total * 100, 1);
        }

        return view('database.town', compact('town', 'first', 'last', 'change'));
    }
}
