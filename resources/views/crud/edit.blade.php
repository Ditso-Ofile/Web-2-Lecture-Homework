{{-- TASK 9 - CRUD: Update --}}
<x-mainpage>
    <x-slot:title>CRUD - Edit {{ $town->tname }}</x-slot:title>

    <div class="container mt-5" style="max-width: 640px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Edit town <span class="text-muted">#{{ $town->id }}</span></h1>

                <form action="{{ route('crud.update', $town) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT') {{-- Form Method Spoofing: HTML forms only know GET and POST --}}
                    @include('crud._form')

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('crud.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-mainpage>
