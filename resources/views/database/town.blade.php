{{-- TASK 5 - One town with its county and full population history (3 tables) --}}
<x-mainpage>
    <x-slot:title>{{ $town->tname }}</x-slot:title>

    <section class="page-header">
        <div class="container">
            <a href="{{ url('/database') }}" class="text-white-50 small text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back to the database
            </a>
            <h1 class="mt-2">{{ $town->tname }}</h1>
            <p>
                {{ $town->county->cname ?? '' }} county
                @if ($town->countyseat) <span class="badge bg-danger ms-2">County seat</span> @endif
                @if ($town->countylevel) <span class="badge bg-success ms-1">County-level town</span> @endif
            </p>
        </div>
    </section>

    <div class="container">
        @if ($town->populations->isEmpty())
            <div class="alert alert-info">There is no population data for this town.</div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <div class="stat-label">Population {{ $first->ryear }}</div>
                            <div class="stat-value">{{ $first->total !== null ? number_format($first->total, 0, '.', ' ') : '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <div class="stat-label">Population {{ $last->ryear }}</div>
                            <div class="stat-value">{{ $last->total !== null ? number_format($last->total, 0, '.', ' ') : '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <div class="stat-label">Change {{ $first->ryear }}&ndash;{{ $last->ryear }}</div>
                            <div class="stat-value {{ $change !== null && $change < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $change !== null ? ($change > 0 ? '+' : '') . $change . ' %' : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive shadow-sm">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Year</th>
                            <th class="text-end">Women</th>
                            <th class="text-end">Men</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($town->populations as $p)
                            <tr>
                                <td>{{ $p->ryear }}</td>
                                <td class="text-end">{{ $p->women !== null ? number_format($p->women, 0, '.', ' ') : '—' }}</td>
                                <td class="text-end">{{ $p->men !== null ? number_format($p->men, 0, '.', ' ') : '—' }}</td>
                                <td class="text-end fw-semibold">{{ $p->total !== null ? number_format($p->total, 0, '.', ' ') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-mainpage>
