{{-- TASK 6 - Contact form. Server-side validation, saved into the messages table --}}
<x-mainpage>
    <x-slot:title>Contact</x-slot:title>

    <section class="page-header">
        <div class="container">
            <h1><i class="bi bi-envelope me-2"></i>Contact us</h1>
            <p>Questions about the data or the site? Send a message to the site owners.</p>
        </div>
    </section>

    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        {{-- Summary of all validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please correct the following:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- novalidate: switch off the browser's checks so the server-side validation is visible --}}
                        <form method="POST" action="{{ route('contact.store') }}" novalidate>
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                           class="form-control @error('subject') is-invalid @enderror">
                                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea id="message" name="message" rows="6"
                                              class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">
                                <i class="bi bi-send me-1"></i>Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h2 class="h6">How it works</h2>
                        <p class="small text-muted">
                            Every message is saved in our database with the time it was sent.
                            Registered users can read all messages in the <strong>Messages</strong> menu.
                        </p>
                        @guest
                            <p class="small mb-0">
                                <a href="{{ url('/register') }}">Register</a> or <a href="{{ url('/login') }}">log in</a> to read the messages.
                            </p>
                        @else
                            <a href="{{ url('/messages') }}" class="btn btn-outline-primary btn-sm">Read the messages</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-mainpage>
