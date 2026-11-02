<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Town;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * TASK 9 - CRUD menu (Create, Read, Update, Delete) for the `towns` table.
 * Resource controller, route: Route::resource('crud', ...)->parameters(['crud' => 'town'])
 * Only the admin can reach it ('auth' + 'admin' middleware).
 *
 *   GET    /crud              index    list (Read)
 *   GET    /crud/create       create   empty form
 *   POST   /crud              store    save new town (Create)
 *   GET    /crud/{town}       show     details (Read)
 *   GET    /crud/{town}/edit  edit     filled form
 *   PUT    /crud/{town}       update   save changes (Update)
 *   DELETE /crud/{town}       destroy  delete (Delete)
 */
class TownCrudController extends Controller
{
    public function index(Request $request): View
    {
        $search   = trim((string) $request->input('q', ''));
        $countyId = $request->integer('county') ?: null;

        $towns = Town::with('county')
            ->withCount('populations')
            ->when($search !== '', fn ($q) => $q->where('tname', 'like', '%' . $search . '%'))
            ->when($countyId, fn ($q) => $q->where('countyid', $countyId))
            ->orderBy('tname')
            ->paginate(15)
            ->withQueryString();

        return view('crud.index', [
            'towns'    => $towns,
            'counties' => County::orderBy('cname')->get(),
            'search'   => $search,
            'countyId' => $countyId,
        ]);
    }

    public function create(): View
    {
        return view('crud.create', ['counties' => County::orderBy('cname')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTown($request);

        try {
            // The ids of towns.txt are not auto-increment, so we give the next free id
            $validated['id'] = (int) Town::max('id') + 1;
            $town = Town::create($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('failed', 'The town could not be saved.');
        }

        return redirect()->route('crud.index')->with('success', "Town \"{$town->tname}\" was added.");
    }

    public function show(Town $town): View
    {
        $town->load(['county', 'populations' => fn ($q) => $q->orderByDesc('ryear')]);

        return view('crud.show', compact('town'));
    }

    public function edit(Town $town): View
    {
        return view('crud.edit', [
            'town'     => $town,
            'counties' => County::orderBy('cname')->get(),
        ]);
    }

    public function update(Request $request, Town $town): RedirectResponse
    {
        $validated = $this->validateTown($request, $town);

        try {
            $town->update($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('failed', 'The town could not be updated.');
        }

        return redirect()->route('crud.index')->with('success', "Town \"{$town->tname}\" was updated.");
    }

    public function destroy(Town $town): RedirectResponse
    {
        $name = $town->tname;

        try {
            // The populations rows point to the town (foreign key), so they are deleted first
            DB::transaction(function () use ($town) {
                $town->populations()->delete();
                $town->delete();
            });
        } catch (\Exception $e) {
            return back()->with('failed', 'The town could not be deleted.');
        }

        return redirect()->route('crud.index')->with('success', "Town \"{$name}\" was deleted.");
    }

    // Server-side validation shared by store() and update()
    private function validateTown(Request $request, ?Town $town = null): array
    {
        $validated = $request->validate([
            'tname'    => ['required', 'string', 'max:100', Rule::unique('towns', 'tname')->ignore($town?->id)],
            'countyid' => ['required', 'integer', 'exists:counties,id'],
        ], [
            'tname.required'    => 'The town name is required.',
            'tname.unique'      => 'A town with this name already exists.',
            'countyid.required' => 'Please choose a county.',
            'countyid.exists'   => 'Please choose a valid county.',
        ]);

        // Unticked checkboxes are not sent by the browser -> false
        $validated['countyseat']  = $request->boolean('countyseat');
        $validated['countylevel'] = $request->boolean('countylevel');

        return $validated;
    }
}
