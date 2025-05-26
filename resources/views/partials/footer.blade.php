<footer class="text-center text-lg-start mt-2">
    @if(url()->current() != route('login') && url()->current() != route('register'))
        <div class="d-flex justify-content-center footer-category">
            <nav class="nav justify-content-between gap-5 mb-1 mt-4 text-start border-bottom border-dark mb-3 w-75">
                <ul class="p-0">
                    <li class="text-dark fs-5 fw-medium">Información</li>
                    <li><a class="nav-link text-dark p-1" href="#terms">Términos y condiciones</a></li>
                    <li><a class="nav-link text-dark p-1" href="#privacy">Política de privacidad</a></li>
                    <li><a class="nav-link text-dark p-1" href="#cookies">Política de cookies</a></li>
                    <li><a class="nav-link text-dark p-1" href="#legal">Aviso legal</a></li>
                </ul>   
                
                <ul>
                    <li class="text-dark fs-5 fw-medium">Contacto</li>
                    <div class="d-flex flex-column">
                        <li><a class="nav-link text-dark p-1" href="mailto:freshyourzone@gmail.com">Email: freshyourzone@gmail.com </a></li>
                        <li><a class="nav-link text-dark p-1">Teléfono: 123-456-789</a></li>
                        <li><a class="nav-link text-dark p-1">Dirección: Calle FZY, 123</a></li>
                    </div>
                </ul>

                <ul>
                    <li class="text-dark fs-5 fw-medium">Navegación</li>
                    <li><a class="nav-link text-dark p-1" href="/">Inicio</a></li>
                    <li><a class="nav-link text-dark p-1" href="/products">Productos</a></li>
                    <li><a class="nav-link text-dark p-1" href="/favorites">Favoritos</a></li>
                    <li><a class="nav-link text-dark p-1" href="/notifications">Notificaciones</a></li>
                    <li><a class="nav-link text-dark p-1" href="/profile">Perfil</a></li>
                </ul>  
            </nav>
        </div>
    @endif

    <div class="text-center p-2 m-2">
        &copy; {{ date('Y') }} FZY - Fresh Your Zone. Todos los derechos reservados.
    </div>
</footer>
