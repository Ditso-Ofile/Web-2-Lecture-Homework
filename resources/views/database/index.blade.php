{{-- TASK 5 - Database menu: data from the counties, towns and populations tables --}}
<x-mainpage>
    <x-slot:title>Database</x-slot:title>

    <section class="page-header">
        <div class="container">
            <h1><i class="bi bi-database me-2"></i>Towns of Hungary</h1>
            <p>Data from 3 tables: <strong>counties</strong>, <strong>towns</strong> and <strong>populations</strong> &middot; census year {{ $year }}</p>
        </div>
    </section>

    <div class="container">

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-towns" type="button" role="tab">
                    <i class="bi bi-buildings me-1"></i>Towns
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-counties" type="button" role="tab">
                    <i class="bi bi-map me-1"></i>Counties
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ===================== TOWNS ===================== --}}
            <div class="tab-pane fade show active" id="tab-towns" role="tabpanel">

                {{-- Filter form (GET, so the filters stay in the URL) --}}
                <form method="GET" action="{{ url('/database') }}" class="card card-body mb-4 shadow-sm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="q">Town name</label>
                            <input type="text" class="form-control" id="q" name="q" value="{{ $search }}" placeholder="e.g. Szeged">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="county">County</label>
                            <select class="form-select" id="county" name="county">
                                <option value="">All counties</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county->id }}" @selected($countyId == $county->id)>{{ $county->cname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label" for="year">Year</label>
                            <select class="form-select" id="year" name="year">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label" for="sort">Sort by</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="name" @selected($sort === 'name')>Name</option>
                                <option value="population" @selected($sort === 'population')>Population</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="seat" name="seat" value="1" @checked($seatOnly)>
                                <label class="form-check-label" for="seat">County seats only</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
                                <a href="{{ url('/database') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="text-muted small">
                    Showing {{ $towns->firstItem() ?? 0 }}&ndash;{{ $towns->lastItem() ?? 0 }} of {{ $towns->total() }} towns
                </p>

                <div class="table-responsive shadow-sm mb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Town</th>
                                <th>County</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Women</th>
                                <th class="text-end">Men</th>
                                <th class="text-end">Total ({{ $year }})</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($towns as $town)
                                @php
                                    $total = $town->pop_total !== null ? (int) $town->pop_total : null;
                                    $women = $town->pop_women !== null ? (int) $town->pop_women : null;
                                    $men   = ($total !== null && $women !== null) ? $total - $women : null;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $town->tname }}</td>
                                    <td>{{ $town->county->cname ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($town->countyseat)
                                            <span class="badge bg-danger">County seat</span>
                                        @endif
                                        @if ($town->countylevel)
                                            <span class="badge bg-success">County-level</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $women !== null ? number_format($women, 0, '.', ' ') : '—' }}</td>
                                    <td class="text-end">{{ $men !== null ? number_format($men, 0, '.', ' ') : '—' }}</td>
                                    <td class="text-end fw-semibold">{{ $total !== null ? number_format($total, 0, '.', ' ') : '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('database.town', $town) }}" class="btn btn-outline-primary btn-sm">History</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No towns match the filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $towns->links('pagination::bootstrap-5') }}
                </div>
            </div>

            {{-- ===================== COUNTIES ===================== --}}
            <div class="tab-pane fade" id="tab-counties" role="tabpanel">
                <div class="table-responsive shadow-sm">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>County</th>
                                <th class="text-end">Towns</th>
                                <th class="text-end">Population of its towns ({{ $year }})</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($countySummary as $i => $county)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $county->cname }}</td>
                                    <td class="text-end">{{ $county->towns_count }}</td>
                                    <td class="text-end">{{ number_format((int) $county->population_sum, 0, '.', ' ') }}</td>
                                    <td class="text-end">
                                        <a href="{{ url('/database') . '?county=' . $county->id . '&year=' . $year }}" class="btn btn-outline-primary btn-sm">Show towns</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mt-2">
                    The dataset contains the towns (cities) of each county, not every village, so these totals are the urban population.
                </p>
            </div>
        </div>
    </div>
</x-mainpage>
