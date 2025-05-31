<footer class="text-center text-lg-start mt-2">
    @if(url()->current() != route('login') && url()->current() != route('register'))
        <div class="d-flex justify-content-center footer-category">
            <nav class="nav justify-content-between gap-5 mb-1 mt-4 text-start border-bottom border-dark mb-3 w-75">
                <ul class="p-0">
                    <li class="text-dark fs-5 fw-medium">{{ __('Información.') }}</li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/terms-conditions">Términos y condiciones</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/privacy-policy">Política de privacidad</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/cookies-policy">Política de cookies</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/legal-policy">Aviso legal</a></li>
                </ul>   
                
                <ul>
                    <li class="text-dark fs-5 fw-medium">{{ __('Contacto.') }}</li>
                    <div class="d-flex flex-column">
                        <li><a class="nav-link text-dark p-1 section-footer" href="/about-us">About Us</a></li>
                        <li><a class="nav-link text-dark p-1 section-footer" href="mailto:freshyourzone@gmail.com">Email: freshyourzone@gmail.com </a></li>
                        <li><a class="nav-link text-dark p-1 section-footer">Teléfono: 123-456-789</a></li>
                        <li><a class="nav-link text-dark p-1 section-footer">Dirección: Calle FZY, 123</a></li>
                    </div>
                </ul>

                <ul>
                    <li class="text-dark fs-5 fw-medium">Navegación</li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/">Inicio</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/products">Productos</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/favorites">Favoritos</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/notifications">Notificaciones</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/profile">Perfil</a></li>
                </ul>  
            </nav>
        </div>
    @endif

    <div class="text-center p-2 m-2">
        &copy; {{ date('Y') }} FZY - Fresh Your Zone. Todos los derechos reservados.
    </div>

    <div class="p-2 m-2 d-flex justify-content-center gap-3">
        {{ session('language') }}
        <a class="nav-link" href="{{ url('/change-language/en') }}">English (US)</a>
        <a class="nav-link" href="{{ url('/change-language/es') }}">Español (ES)</a>
    </div>
</footer>
