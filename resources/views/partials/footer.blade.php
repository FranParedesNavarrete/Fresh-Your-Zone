<footer class="text-center text-lg-start mt-2">
    @if(url()->current() != route('login') && url()->current() != route('register'))
        <div class="d-flex justify-content-center footer-category">
            <nav class="nav justify-content-between gap-5 mb-1 mt-4 text-start border-bottom border-dark mb-3 w-75">
                <ul class="p-0">
                    <li class="text-dark fs-5 fw-medium">{{ __('Información.') }}</li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/terms-conditions">{{ __('Términos.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/privacy-policy">{{ __('PolíticaPriv.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/cookies-policy">{{ __('PolíticaCookies.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/information/legal-policy">{{ __('AvisoLegal.') }}</a></li>
                </ul>   
                
                <ul>
                    <li class="text-dark fs-5 fw-medium">{{ __('Contacto.') }}</li>
                    <div class="d-flex flex-column">
                        <li><a class="nav-link text-dark p-1 section-footer" href="/about-us">{{ __('SobreNosotros.') }}</a></li>
                        <li><a class="nav-link text-dark p-1 section-footer" href="mailto:freshyourzone@gmail.com">Email: freshyourzone@gmail.com </a></li>
                        <li><a class="nav-link text-dark p-1 section-footer">{{ __('Teléfono.') }}: 123-456-789</a></li>
                        <li><a class="nav-link text-dark p-1 section-footer">{{ __('Dirección.') }}: Calle FZY, 123</a></li>
                    </div>
                </ul>

                <ul>
                    <li class="text-dark fs-5 fw-medium">{{ __('Navegación.') }}</li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/">{{ __('Perfil.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/products">{{ __('Productos.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/favorites">{{ __('Favoritos.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/notifications">{{ __('Notificaciones.') }}</a></li>
                    <li><a class="nav-link text-dark p-1 section-footer" href="/profile">{{ __('Perfil.') }}</a></li>
                </ul>  
            </nav>
        </div>
    @endif

    <div class="text-center p-2 m-2">
        &copy; {{ date('Y') }} FZY - Fresh Your Zone. {{ __('DerechosReservados.') }}.
    </div>

    <div class="p-2 m-2 d-flex justify-content-center gap-3">
        <a class="nav-link" href="{{ url('/change-language/en') }}">English (US)</a>
        <a class="nav-link" href="{{ url('/change-language/es') }}">Español (ES)</a>
    </div>
</footer>
