<nav class="navbar navbar-expand-md navbar-light bg-light sub-navbar fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand sub-navbar" href="/dgmoss/">
            <img src="/dgmoss/assets/img/logo/logoBlanco.png" alt="logo">  
        </a>

        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav ms-auto"> <!-- Alinea todos los elementos a la derecha -->
                <li class="nav-item">
                    <span class="nav-link mt-2"><?php echo $_SESSION['nombre_usuario'] ?? 'Invitado';?></span>
                </li>
                <li class="nav-item">
                    <a href="/dgmoss/sign-in/logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
