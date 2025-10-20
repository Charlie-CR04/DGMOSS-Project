<?php

//Nos aseguramos que haya sesión
if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

//Requiere usuario logueado
function requireAuth(): void {
    if(!isset($_SESSION['id_usuario'])) {
        header('Location: /dgmoss/sign-in/Index_login.php');
        exit;
    }
}

//Para el Admin
function requireAdmin(): void {
    requireAuth();
    if(($_SESSION['rol'] ?? '') !== 'admin') {
        header('Location: /dgmoss/sign-in/Index_login.php');
        exit;
    }
}

//Para editor o admin
function requireEditorOrAdmin(): void {
    requireAuth();
    $rol = $_SESSION['rol'] ?? '';
    if($rol !== 'admin' && $rol !== 'editor') {
        header('Location: /dgmoss/sign-in/Index_login.php');
        exit;
    }
}

//Para editar documentos
function requireDireccion(?int $id_direccion_param): void {
    requireAuth();
    if(($_SESSION['rol'] ?? '') === 'admin') return;
    
    $miDir = (int)($_SESSION['id_direccion'] ?? 0);

    // Si no se pasó id_direccion o es inválido, se toma la del editor
    if(!$id_direccion_param || $id_direccion_param <= 0) {
        $id_direccion_param = $miDir;
    }

    if($miDir !== $id_direccion_param) {
        header('Location: /dgmoss/admin/panel.php');
        exit;
    }
}


//Token de seguridad CSRF
function ensureCsrfToken(): string {
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

//Validador de token de seguridad
function verifyCsrfOrDie(): void {
    $ok = isset($_POST['csrf'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf']);
    if(!$ok) {
        http_response_code(400);
        exit('CSRF_inválido');
    }
}
