{{-- TASK 9 - CRUD: list of towns (Read) with Edit / Delete buttons --}}
<x-mainpage>
    <x-slot:title>CRUD - Towns</x-slot:title>

    <section class="page-header">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1><i class="bi bi-pencil-square me-2"></i>Manage towns</h1>
                <p>Create, read, update and delete records of the <strong>towns</strong> table (admin only)</p>
            </div>
            <a href="{{ route('crud.create') }}" class="btn btn-light"><i class="bi bi-plus-lg me-1"></i>New town</a>
        </div>
    </section>

    <div class="container">
        <form method="GET" action="{{ route('crud.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Search by town name">
            </div>
            <div class="col-md-4">
                <select name="county" class="form-select">
                    <option value="">All counties</option>
                    @foreach ($counties as $county)
                        <option value="{{ $county->id }}" @selected($countyId == $county->id)>{{ $county->cname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Search</button>
                <a href="{{ route('crud.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive shadow-sm mb-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Town</th>
                        <th>County</th>
                        <th class="text-center">County seat</th>
                        <th class="text-center">County-level</th>
                        <th class="text-end">Population rows</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($towns as $town)
                        <tr>
                            <td>{{ $town->id }}</td>
                            <td class="fw-semibold">{{ $town->tname }}</td>
                            <td>{{ $town->county->cname ?? '—' }}</td>
                            <td class="text-center">{!! $town->countyseat ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<span class="text-muted">–</span>' !!}</td>
                            <td class="text-center">{!! $town->countylevel ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<span class="text-muted">–</span>' !!}</td>
                            <td class="text-end">{{ $town->populations_count }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('crud.show', $town) }}" class="btn btn-outline-secondary btn-sm">View</a>
                                <a href="{{ route('crud.edit', $town) }}" class="btn btn-warning btn-sm">Edit</a>
                                {{-- DELETE needs a form + Form Method Spoofing --}}
                                <form action="{{ route('crud.destroy', $town) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete {{ addslashes($town->tname) }} and its {{ $town->populations_count }} population records?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No towns found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $towns->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-mainpage>
