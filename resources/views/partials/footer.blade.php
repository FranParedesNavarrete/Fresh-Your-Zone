<footer class="text-center text-lg-start mt-2">
    @if(url()->current() != route('login') && url()->current() != route('register'))
        <div class="d-flex justify-content-center footer-category">
            <nav class="nav justify-content-center mb-1">
                <a class="nav-link text-dark" href="#home">Home</a>
                <a class="nav-link text-dark" href="#about">About</a>
                <a class="nav-link text-dark" href="#services">Services</a>
                <a class="nav-link text-dark" href="#contact">Contact</a>
            </nav>
        </div>
    @endif

    <div class="text-center p-2">
        &copy; {{ date('Y') }} FZY - Fresh Your Zone. Todos los derechos reservados.
    </div>
</footer>
