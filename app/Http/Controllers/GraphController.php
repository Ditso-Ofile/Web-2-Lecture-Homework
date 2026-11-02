<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Population;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * TASK 8 - Graph menu.
 * Prepares data from the database (Eloquent ORM) for 4 Chart.js charts.
 */
class GraphController extends Controller
{
    // Default towns for the line chart: the 5 largest county seats
    private const DEFAULT_TOWNS = ['Debrecen', 'Szeged', 'Miskolc', 'Pécs', 'Győr'];

    public function index(Request $request): View
    {
        $years = Population::query()->select('ryear')->distinct()->orderBy('ryear')->pluck('ryear');

        $year = (int) $request->input('year', $years->last());
        if (! $years->contains($year)) {
            $year = (int) $years->last();
        }

        // ---------- Chart 1: population trend of the selected towns (line) ----------
        $selectedIds = collect($request->input('towns', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->take(6)
            ->values();

        if ($selectedIds->isEmpty()) {
            $selectedIds = Town::whereIn('tname', self::DEFAULT_TOWNS)->pluck('id');
        }

        $trendTowns = Town::with(['populations' => fn ($q) => $q->orderBy('ryear')])
            ->whereIn('id', $selectedIds)
            ->orderBy('tname')
            ->get();

        $trend = [
            'labels'   => $years->values(),
            'datasets' => $trendTowns->map(fn (Town $town) => [
                'label' => $town->tname,
                // one value per year (null where the year is missing)
                'data'  => $years->map(fn ($y) => optional($town->populations->firstWhere('ryear', $y))->total)->values(),
            ])->values(),
        ];

        // ---------- Chart 2: the 10 largest towns in the selected year (bar) ----------
        $top = Population::with('town')
            ->where('ryear', $year)
            ->whereNotNull('total')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topTowns = [
            'labels' => $top->map(fn ($p) => $p->town->tname ?? '?')->values(),
            'data'   => $top->pluck('total')->values(),
        ];

        // ---------- Chart 3: town population per county in the selected year (doughnut) ----------
        $counties = County::query()
            ->withSum(['populations as population_sum' => fn ($q) => $q->where('ryear', $year)], 'total')
            ->orderByDesc('population_sum')
            ->get();

        $byCounty = [
            'labels' => $counties->pluck('cname')->values(),
            'data'   => $counties->map(fn ($c) => (int) $c->population_sum)->values(),
        ];

        // ---------- Chart 4: women and men in all towns, by year (stacked bar) ----------
        $totals = Population::query()
            ->selectRaw('ryear, SUM(women) as women, SUM(total) as total')
            ->groupBy('ryear')
            ->orderBy('ryear')
            ->get();

        $gender = [
            'labels' => $totals->pluck('ryear')->values(),
            'women'  => $totals->map(fn ($r) => (int) $r->women)->values(),
            'men'    => $totals->map(fn ($r) => (int) $r->total - (int) $r->women)->values(),
        ];

        return view('graph.index', [
            'years'       => $years,
            'year'        => $year,
            'allTowns'    => Town::orderBy('tname')->get(['id', 'tname']),
            'selectedIds' => $selectedIds->all(),
            'trend'       => $trend,
            'topTowns'    => $topTowns,
            'byCounty'    => $byCounty,
            'gender'      => $gender,
        ]);
    }
}
