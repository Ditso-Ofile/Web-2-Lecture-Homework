{{-- TASK 9 - CRUD: Read one record --}}
<x-mainpage>
    <x-slot:title>CRUD - {{ $town->tname }}</x-slot:title>

    <div class="container mt-5" style="max-width: 760px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h1 class="h4 mb-0">{{ $town->tname }} <span class="text-muted">#{{ $town->id }}</span></h1>
                    <a href="{{ route('crud.edit', $town) }}" class="btn btn-warning btn-sm">Edit</a>
                </div>

                <dl class="row mb-4">
                    <dt class="col-sm-4">County</dt>
                    <dd class="col-sm-8">{{ $town->county->cname ?? '—' }}</dd>
                    <dt class="col-sm-4">County seat</dt>
                    <dd class="col-sm-8">{{ $town->countyseat ? 'Yes' : 'No' }}</dd>
                    <dt class="col-sm-4">County-level town</dt>
                    <dd class="col-sm-8">{{ $town->countylevel ? 'Yes' : 'No' }}</dd>
                    <dt class="col-sm-4">Population records</dt>
                    <dd class="col-sm-8">{{ $town->populations->count() }}</dd>
                </dl>

                @if ($town->populations->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead><tr><th>Year</th><th class="text-end">Women</th><th class="text-end">Total</th></tr></thead>
                            <tbody>
                                @foreach ($town->populations as $p)
                                    <tr>
                                        <td>{{ $p->ryear }}</td>
                                        <td class="text-end">{{ $p->women !== null ? number_format($p->women, 0, '.', ' ') : '—' }}</td>
                                        <td class="text-end">{{ $p->total !== null ? number_format($p->total, 0, '.', ' ') : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <a href="{{ route('crud.index') }}" class="btn btn-outline-secondary mt-4">Back to the list</a>
            </div>
        </div>
    </div>
</x-mainpage>
