<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php");
require_once("modelo/AccesoDatos.php");

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$oUsu = unserialize($_SESSION["usuario"]);
$rol = $oUsu->getRol();

if ($rol !== "admin") {
    echo "<h2>No tienes permisos para acceder a esta página.</h2>";
    exit;
}

$acceso = new AccesoDatos();
$acceso->conectar();
$pdo = $acceso->getPDO();

// Consulta para obtener usuarios únicos con rol 'usuario'
$sql = "SELECT DISTINCT u.idUsuario, u.nombre, u.correo, u.telefono, u.direccion
        FROM usuarios u 
        LEFT JOIN donaciones d ON u.idUsuario = d.idUsuario 
        WHERE u.rol = 'usuario'";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

$acceso->desconectar();
?>
<div style="display: flex;">

    <?php include_once("vista/aside.php"); ?>
    
    <head>
        <meta charset="UTF-8">
        <title>Donantes</title>
    </head>
    <section class="principal">
        <h1>Donantes</h1>
        <br>
        <input type="text" id="buscador" placeholder="Buscar donante por nombre..." style="margin-bottom: 10px; padding: 5px; width: 100%; max-width: 300px;">

        <?php if (count($resultado) > 0): ?>
            <div class="tabla-scroll">
            <table class="donantes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['idUsuario']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['correo']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono']) ?></td>
                            <td><?= htmlspecialchars($fila['direccion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <!-- Modal -->
            <div id="modalPerfil" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                background-color:rgba(0,0,0,0.6); justify-content:center; align-items:center;">
                <div style="background:#fff; padding:20px; border-radius:10px; max-width:500px; text-align:center;" id="contenidoPerfil">
                    <button onclick="cerrarModal()" style="float:right;">X</button>
                    <h2>Perfil del Usuario</h2>
                    <div id="infoUsuario"></div>
                </div>
            </div>

        <?php else: ?>
            <p style="text-align: center;">No hay usuarios registrados.</p>
        <?php endif; ?>

    </section>
    <script src="controlador/modalPerfil.js"></script>
    <script src="controlador/busqueda.js"></script>
</div>

<?php
include_once("vista/pie.html");
?>
