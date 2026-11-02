{{-- TASK 4 - Mainpage: introduces the application --}}
<x-mainpage>
    <x-slot:title>Mainpage</x-slot:title>

    {{-- ===================== HERO ===================== --}}
    <section class="hero">
        <div class="container">
            <span class="hero-flag"></span>
            <h1 class="mb-3">Hungary Towns Explorer</h1>
            <p class="lead mb-4">
                Discover the towns of Hungary&rsquo;s {{ $stats['counties'] }} counties and how their population
                changed between {{ $firstYear ?? '—' }} and {{ $latestYear ?? '—' }}. Browse the data, compare towns
                on interactive charts and send us your questions.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ url('/database') }}" class="btn btn-light btn-lg"><i class="bi bi-database me-1"></i>Explore the data</a>
                <a href="{{ url('/graph') }}" class="btn btn-outline-light btn-lg"><i class="bi bi-bar-chart-line me-1"></i>See the charts</a>
            </div>
        </div>
    </section>

    <div class="container">

        {{-- ===================== STATS ===================== --}}
        <section class="row g-3" style="margin-top: -2.5rem;">
            <div class="col-6 col-lg-3">
                <div class="card stat-card shadow h-100">
                    <div class="card-body">
                        <div class="stat-value">{{ $stats['counties'] }}</div>
                        <div class="stat-label">Counties</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card stat-card shadow h-100">
                    <div class="card-body">
                        <div class="stat-value">{{ number_format($stats['towns'], 0, '.', ' ') }}</div>
                        <div class="stat-label">Towns</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card stat-card shadow h-100">
                    <div class="card-body">
                        <div class="stat-value">{{ $stats['countySeats'] }}</div>
                        <div class="stat-label">County seats</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card stat-card shadow h-100">
                    <div class="card-body">
                        <div class="stat-value">{{ number_format($stats['population'] / 1000000, 2) }}M</div>
                        <div class="stat-label">Town residents in {{ $latestYear }}</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== LARGEST TOWNS ===================== --}}
        <section class="mt-5">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <h2 class="h3 mb-0">Largest towns</h2>
                    <small class="text-muted">Population in {{ $latestYear }}</small>
                </div>
                <a href="{{ url('/database?sort=population') }}" class="btn btn-outline-primary btn-sm">All towns</a>
            </div>

            @php $max = $largest->max('total') ?: 1; @endphp
            <div class="row g-3">
                @forelse ($largest as $i => $p)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('database.town', $p->townid) }}" class="text-decoration-none text-reset">
                            <div class="card card-hover shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-primary">#{{ $i + 1 }}</span>
                                        <small class="text-muted">{{ $p->town->county->cname ?? '' }}</small>
                                    </div>
                                    <h3 class="h5 mt-2 mb-1">{{ $p->town->tname ?? '' }}</h3>
                                    <div class="fs-4 fw-bold">{{ number_format($p->total, 0, '.', ' ') }}</div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: {{ round($p->total / $max * 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-info">No data yet &ndash; run the seeders.</div></div>
                @endforelse
            </div>
        </section>

        {{-- ===================== FEATURES ===================== --}}
        <section class="mt-5">
            <h2 class="h3 mb-3">What you can do here</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-hover shadow-sm h-100">
                        <div class="card-body">
                            <span class="feature-icon text-primary mb-3"><i class="bi bi-table"></i></span>
                            <h3 class="h6">Database</h3>
                            <p class="small text-muted mb-2">Search towns, filter by county or year and open each town&rsquo;s population history.</p>
                            <a href="{{ url('/database') }}" class="stretched-link small">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-hover shadow-sm h-100">
                        <div class="card-body">
                            <span class="feature-icon text-danger mb-3"><i class="bi bi-graph-up"></i></span>
                            <h3 class="h6">Graph</h3>
                            <p class="small text-muted mb-2">Interactive Chart.js charts built from the population data.</p>
                            <a href="{{ url('/graph') }}" class="stretched-link small">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-hover shadow-sm h-100">
                        <div class="card-body">
                            <span class="feature-icon text-success mb-3"><i class="bi bi-envelope"></i></span>
                            <h3 class="h6">Contact</h3>
                            <p class="small text-muted mb-2">Send a message to the site owners &ndash; registered users can read the messages.</p>
                            <a href="{{ url('/contact') }}" class="stretched-link small">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-hover shadow-sm h-100">
                        <div class="card-body">
                            <span class="feature-icon text-warning mb-3"><i class="bi bi-calculator"></i></span>
                            <h3 class="h6">Test</h3>
                            <p class="small text-muted mb-2">A calculator covered by automated Unit and Feature tests.</p>
                            <a href="{{ url('/calc') }}" class="stretched-link small">Open</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ABOUT + COUNTY SEATS ===================== --}}
        <section class="mt-5 row g-4">
            <div class="col-lg-5">
                <h2 class="h3">About the data</h2>
                <p>
                    Hungary is divided into 19 counties plus the capital, Budapest, which is listed here as its own unit
                    with its 23 districts. Each county is run from a <strong>county seat</strong>, and the largest towns
                    have <strong>county-level</strong> rights, which means they govern themselves like a county.
                </p>
                <p class="mb-0">
                    The database records the number of women and the total population of every town for each year
                    from {{ $firstYear ?? '—' }} to {{ $latestYear ?? '—' }}, so you can follow how towns have grown or shrunk.
                </p>
            </div>
            <div class="col-lg-7">
                <h2 class="h3">County seats</h2>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($countySeats as $town)
                        <a href="{{ route('database.town', $town) }}" class="btn btn-outline-dark btn-sm">
                            {{ $town->tname }}
                            <span class="text-muted small">&middot; {{ $town->county->cname ?? '' }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== CALL TO ACTION ===================== --}}
        @guest
            <section class="mt-5 p-4 p-md-5 bg-light rounded-3 text-center">
                <h2 class="h4">Join us</h2>
                <p class="text-muted">Create a free account to read the messages sent through the Contact page.</p>
                <a href="{{ url('/register') }}" class="btn btn-primary">Register</a>
                <a href="{{ url('/login') }}" class="btn btn-outline-primary">Login</a>
            </section>
        @endguest
    </div>
</x-mainpage>
