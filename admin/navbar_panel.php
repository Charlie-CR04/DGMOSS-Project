<nav class="navbar-admin">
    <div class="navbar-admin-container">
        <!--    Logo    -->
        <div class="navbar-admin-logo">
            <a href="/dgmoss-project/">
                <img src="/dgmoss-project/assets/img/Logo_GM1.png" alt="Logo_Institución">
            </a>
        </div>

        <!--    Panel derecho: Usuario y cerrar sesión  -->
        <div class="navbar-admin-user">
            <!--    Icono y nombre del usuario  -->
            <div class="usuario-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['nombre'] ?? 'Invitado';?></span>
            </div>
            <!--    Botón de cerrar sesión  -->
            <a href="/dgmoss-project/sign-in/logout.php" class="btn-cerrar-sesion">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </a>
        </div>
    </div>
</nav>