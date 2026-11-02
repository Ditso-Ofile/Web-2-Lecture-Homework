{{-- TEST MENU - Calculator form (the application tested by tests/Unit and tests/Feature) --}}
<x-mainpage>
    <x-slot:title>Test - Calculator</x-slot:title>

    <section class="page-header">
        <div class="container">
            <h1><i class="bi bi-calculator me-2"></i>Calculator</h1>
            <p>A small form application covered by automated Unit and Feature tests (<code class="text-white-50">php artisan test</code>)</p>
        </div>
    </section>

    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">

                {{-- Result --}}
                @if ($result !== null)
                    <div class="alert alert-success" data-testid="result">
                        <div class="small text-uppercase">Calculation result</div>
                        <div class="calc-result">{{ $a }} {{ $symbol }} {{ $b }} = <strong>{{ $result }}</strong></div>
                    </div>
                @endif

                {{-- Error from the Calculator (e.g. division by zero) --}}
                @if ($error !== null)
                    <div class="alert alert-danger" data-testid="error">
                        <div class="small text-uppercase">Error</div>
                        <div class="fs-5">{{ $error }}</div>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Calculator form</h2>

                        <form method="POST" action="{{ route('calc.calculate') }}" novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-sm-5">
                                    <label for="a" class="form-label">A number</label>
                                    <input id="a" type="text" inputmode="decimal" name="a" value="{{ $a }}"
                                           class="form-control @error('a') is-invalid @enderror">
                                    @error('a') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="op" class="form-label">Operation</label>
                                    <select id="op" name="op" class="form-select @error('op') is-invalid @enderror">
                                        @foreach ($operations as $key => $sign)
                                            <option value="{{ $key }}" @selected($op === $key)>{{ $sign }}</option>
                                        @endforeach
                                    </select>
                                    @error('op') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-5">
                                    <label for="b" class="form-label">B number</label>
                                    <input id="b" type="text" inputmode="decimal" name="b" value="{{ $b }}"
                                           class="form-control @error('b') is-invalid @enderror">
                                    @error('b') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">Send</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- History stored in the session --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="h5 mb-0">Last calculations</h2>
                            @if (! empty($history))
                                <form method="POST" action="{{ route('calc.clear') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Clear</button>
                                </form>
                            @endif
                        </div>
                        @if (empty($history))
                            <p class="text-muted small mb-0">No calculations yet.</p>
                        @else
                            <ol class="list-group list-group-numbered" data-testid="history">
                                @foreach ($history as $line)
                                    <li class="list-group-item">{{ $line }}</li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 bg-light border-0">
            <div class="card-body small">
                <strong>Operations:</strong> addition (+), subtraction (-), multiplication (*), division (/),
                power (^) and remainder (mod). Inputs must be numbers between -1 000 000 and 1 000 000.
                Division by zero and invalid powers show an error message instead of crashing.
            </div>
        </div>
    </div>
</x-mainpage>
