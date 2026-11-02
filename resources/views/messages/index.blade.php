{{-- TASK 7 - Messages menu: messages from the Contact form, newest first --}}
<x-mainpage>
    <x-slot:title>Messages</x-slot:title>

    <section class="page-header">
        <div class="container">
            <h1><i class="bi bi-envelope-paper me-2"></i>Messages</h1>
            <p>Messages sent through the Contact page &middot; newest first &middot; {{ $messages->total() }} in total</p>
        </div>
    </section>

    <div class="container">
        @forelse ($messages as $message)
            <div class="card message-card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                        <div>
                            <h2 class="h6 mb-0">{{ $message->subject ?: '(no subject)' }}</h2>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $message->name }}
                                &middot;
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </small>
                        </div>
                        <div class="text-md-end">
                            {{-- Sending time of the message --}}
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-clock me-1"></i>{{ $message->created_at->format('Y-m-d H:i:s') }}
                            </span>
                            <div class="small text-muted">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <p class="message-body mb-0">{{ $message->message }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                There are no messages yet. <a href="{{ url('/contact') }}">Send the first one</a>.
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $messages->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-mainpage>
