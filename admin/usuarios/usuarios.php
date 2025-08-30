<?php
    include(__DIR__ . '/../../includes/auth.php');
    include(__DIR__ . '/../../includes/conexion.php');
    requireAdmin();
    $csrf = ensureCsrfToken();

    $sql = "SELECT 
                    u.id_usuario,
                    u.nombre_usuario, 
                    u.correo, 
                    u.rol, 
                    d.nombre_direccion 
            FROM usuarios u
            LEFT JOIN direcciones d ON u.id_direccion = d.id_direccion
            ORDER BY u.nombre_usuario";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $rows = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de usuarios</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/panel.css">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</head>
<body>
    <?php include(__DIR__ . '/../../admin/navbar_panel.php'); ?>
    <div class="container usuarios-front mt-5">
        <h2 class="text-center mb-5">Listado de Usuarios</h2>
        <a class="btn btn-primary mb-5" href="/dgmoss-project/admin/formulario_usuarios.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
            </svg>
            Agregar usuario
        </a>

        <div class="table-responsive table-container">
            <?php if(empty($rows)): ?>
                <div class="alert alert-warning" role="alert">
                    No hay usuarios registrados.
                </div>
            <?php else:?>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Correo</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($row['nombre_usuario']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['correo']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['rol']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['nombre_direccion'] ?? '----'); ?></td>
                                <td class="text-center">
                                    <form action="/dgmoss-project/admin/eliminar.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="id" value="<?= (int)$row['id_usuario'] ?>">
                                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>