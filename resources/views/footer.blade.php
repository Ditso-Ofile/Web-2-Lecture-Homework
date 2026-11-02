{{-- TASK 2 - Footer with the creators' names and Neptun codes (shown on every page) --}}
<footer class="site-footer bg-dark text-light mt-5">
    <div class="container py-4">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6">
                <h6 class="text-white mb-1"><i class="bi bi-geo-alt-fill me-1"></i>Hungary Towns Explorer</h6>
                <small class="text-white-50">
                    Web-Programming II homework &middot; Laravel {{ app()->version() }}
                </small>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="small text-white-50 mb-1">Created by</div>
                <div>Salokhov Jahongir <span class="badge bg-secondary">PQJEK6</span></div>
                <div>Mogajane Ditso Ofile <span class="badge bg-secondary">HH0DUU</span></div>
            </div>
        </div>
        <hr class="border-secondary my-3">
        <div class="small text-white-50 text-center">
            Theme: <a href="https://bootswatch.com/lux/" class="text-white-50" target="_blank" rel="noopener">Bootswatch Lux</a> (MIT licence)
            &middot; &copy; {{ date('Y') }}
        </div>
    </div>
</footer>
