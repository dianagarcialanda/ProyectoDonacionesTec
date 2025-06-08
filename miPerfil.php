<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php");
include_once("modelo/AccesoDatos.php");

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$oUsu = unserialize($_SESSION["usuario"]);
$idUsuario = $oUsu->getIdUsuario();

$pdo = AccesoDatos::obtenerInstancia();

$sql = "SELECT idUsuario, nombre, correo, contrasena, telefono, direccion, fechaNac, fotoPerfil, rol 
        FROM usuarios WHERE idUsuario = :idUsuario";

$stmt = $pdo->getPDO()->prepare($sql);
$stmt->execute([':idUsuario' => $idUsuario]);

$fila = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div style="display: flex;">
    <?php include_once("vista/aside.php"); ?>
    
    <section class="principal">
        <br>
        <?php if ($fila): ?>
            <button onclick="abrirPopup()" class="boton-modificar">Modificar Información</button>
            <!-- Modal emergente oculto por defecto -->
            <div id="popup" class="modal">
                <div class="modal-contenido">
                    <span class="cerrar" onclick="cerrarPopup()">&times;</span>
                    <h2>Modificar tu perfil</h2>
                    <form method="POST" action="actualizarPerfil.php" onsubmit="return enviarFormulario(this)">
                        <label>Nombre:</label><br>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($fila["nombre"]) ?>"><br><br>

                        <label>Correo:</label><br>
                        <input type="email" name="correo" value="<?= htmlspecialchars($fila["correo"]) ?>"><br><br>

                        <label>Teléfono:</label><br>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($fila["telefono"]) ?>"><br><br>

                        <label>Dirección:</label><br>
                        <input type="text" name="direccion" value="<?= htmlspecialchars($fila["direccion"]) ?>"><br><br>

                        <button type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>
            <div>
                <br>
                <h1>Tu perfil</h1>
                <h2>Foto de Perfil:</h2>
                <img src="<?= htmlspecialchars($fila["fotoPerfil"]) ?>" alt="Foto de Perfil" width="150"><br><br>
                <h2>ID Usuario: <?= htmlspecialchars($fila["idUsuario"]) ?></h2>
                <h2>Nombre: <?= htmlspecialchars($fila["nombre"]) ?></h2>
                <h2>Correo: <?= htmlspecialchars($fila["correo"]) ?></h2>
                <h2>Teléfono: <?= htmlspecialchars($fila["telefono"]) ?></h2>
                <h2>Dirección: <?= htmlspecialchars($fila["direccion"]) ?></h2>
                <h2>Fecha de Nacimiento: <?= htmlspecialchars($fila["fechaNac"]) ?></h2>
                <h2>Rol: <?= htmlspecialchars($fila["rol"]) ?></h2>  
            </div>
        <?php else: ?>
            <p style="text-align: center;">No estás registrado</p>
        <?php endif; ?>
    </section>

    <script src="controlador/popup.js"></script>
</div>

<?php
include_once("vista/pie.html");
?>
