{{-- TASK 9 - CRUD: Create --}}
<x-mainpage>
    <x-slot:title>CRUD - New town</x-slot:title>

    <div class="container mt-5" style="max-width: 640px;">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Add new town</h1>

                <form action="{{ route('crud.store') }}" method="POST" novalidate>
                    @csrf
                    @include('crud._form')

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('crud.index') }}" class="btn btn-outline-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-mainpage>
