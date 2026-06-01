<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-map-location-dot"></i> {{ $title }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">
                        <i class="fa-solid fa-house-chimney me-1"></i> Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('map') }}">
                        <i class="fa-solid fa-map me-1"></i> Peta
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">
                        <i class="fa-solid fa-table me-1"></i> Tabel
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">
                        <i class="fa-solid fa-circle-info me-1"></i> Tentang
                    </a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm px-3 ms-2"
                            href="{{ route('login') }}"
                            style="background-color: #d291bc; color: white; border-radius: 8px;">
                            <i class="fa-solid fa-user me-1"></i> Login
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf

                            <button type="submit"
                                class="nav-link btn btn-sm px-3 ms-2"
                                style="background-color: #b61111; color: white; border-radius: 8px;">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                            </button>

                        </form>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
