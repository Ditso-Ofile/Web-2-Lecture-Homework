{{-- TASK 8 - Graph menu: 4 charts drawn with Chart.js (https://www.chartjs.org/) from database data --}}
<x-mainpage>
    <x-slot:title>Graph</x-slot:title>

    <section class="page-header">
        <div class="container">
            <h1><i class="bi bi-bar-chart-line me-2"></i>Population charts</h1>
            <p>Charts built from the populations, towns and counties tables with Chart.js</p>
        </div>
    </section>

    <div class="container">

        {{-- Options for the charts (GET form) --}}
        <form method="GET" action="{{ url('/graph') }}" class="card card-body shadow-sm mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-7">
                    <label for="towns" class="form-label">Towns on the trend chart (max. 6, hold Ctrl to select more)</label>
                    <select id="towns" name="towns[]" class="form-select" multiple size="5">
                        @foreach ($allTowns as $town)
                            <option value="{{ $town->id }}" @selected(in_array($town->id, $selectedIds))>{{ $town->tname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Year for the bar and doughnut charts</label>
                    <select id="year" name="year" class="form-select">
                        @foreach ($years->reverse() as $y)
                            <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ url('/graph') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">Population of the selected towns, {{ $years->first() }}&ndash;{{ $years->last() }}</h2>
                        <div style="height: 360px;"><canvas id="trendChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5">The 10 largest towns in {{ $year }}</h2>
                        <div style="height: 360px;"><canvas id="topChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5">Town population by county, {{ $year }}</h2>
                        <div style="height: 360px;"><canvas id="countyChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">Women and men in all towns, by year</h2>
                        <div style="height: 340px;"><canvas id="genderChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            // Data prepared by GraphController, passed from PHP to JavaScript as JSON
            const trend    = @json($trend);
            const topTowns = @json($topTowns);
            const byCounty = @json($byCounty);
            const gender   = @json($gender);

            const palette = ['#cd2a3e', '#436f4d', '#1a1a1a', '#e0a100', '#2b6cb0', '#8e44ad',
                             '#16a085', '#d35400', '#7f8c8d', '#c0392b', '#27ae60', '#34495e',
                             '#f39c12', '#2980b9', '#9b59b6', '#1abc9c', '#e67e22', '#95a5a6',
                             '#b03a2e', '#117a65'];
            const fmt = (n) => Number(n).toLocaleString('hu-HU');

            Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;

            // 1) Line chart
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: trend.labels,
                    datasets: trend.datasets.map((d, i) => ({
                        label: d.label, data: d.data, tension: 0.25, spanGaps: true,
                        borderColor: palette[i], backgroundColor: palette[i], pointRadius: 3,
                    })),
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${fmt(c.parsed.y)}` } } },
                    scales: { y: { ticks: { callback: fmt } } },
                },
            });

            // 2) Horizontal bar chart
            new Chart(document.getElementById('topChart'), {
                type: 'bar',
                data: {
                    labels: topTowns.labels,
                    datasets: [{ label: 'Population', data: topTowns.data, backgroundColor: '#cd2a3e' }],
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => fmt(c.parsed.x) } } },
                    scales: { x: { ticks: { callback: fmt } } },
                },
            });

            // 3) Doughnut chart
            new Chart(document.getElementById('countyChart'), {
                type: 'doughnut',
                data: {
                    labels: byCounty.labels,
                    datasets: [{ data: byCounty.data, backgroundColor: palette, borderWidth: 1 }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } },
                        tooltip: { callbacks: { label: (c) => `${c.label}: ${fmt(c.parsed)}` } },
                    },
                },
            });

            // 4) Stacked bar chart
            new Chart(document.getElementById('genderChart'), {
                type: 'bar',
                data: {
                    labels: gender.labels,
                    datasets: [
                        { label: 'Women', data: gender.women, backgroundColor: '#cd2a3e' },
                        { label: 'Men', data: gender.men, backgroundColor: '#436f4d' },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${fmt(c.parsed.y)}` } } },
                    scales: { x: { stacked: true }, y: { stacked: true, ticks: { callback: fmt } } },
                },
            });
        </script>
    @endpush
</x-mainpage>
