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
$idUsuario = $oUsu->getIdUsuario();

// Conexión usando AccesoDatos (PDO)
$oAD = new AccesoDatos();
if (!$oAD->conectar()) {
    die("Error de conexión a la base de datos.");
}

$pdo = $oAD->getPDO();

$sql = "
    SELECT d.idDonacion, d.tipoDonacion, d.fecha, d.estado,
        dp.descripcion AS descripcion_propuesta,
        de.descripcion AS descripcion_especie,
        de.cantidad AS cantidad_especie
    FROM donaciones d
    LEFT JOIN donacionespropuesta dp ON d.idDonacion = dp.idDonacion
    LEFT JOIN donacionesespecie de ON d.idDonacion = de.idDonacion
    WHERE d.idUsuario = ?
    ORDER BY d.fecha DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idUsuario]);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex;">
    <?php include_once("vista/aside.php"); ?>
    <section class="principal">
        <h2>Mi Historial de Donaciones</h2>
        <?php if (count($resultado) > 0): ?>
            <table class="tabla1">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha']) ?></td>
                            <td><?= htmlspecialchars($fila['tipoDonacion']) ?></td>
                            <td><?= htmlspecialchars($fila['estado']) ?></td>
                            <td>
                                <?php 
                                    if ($fila['tipoDonacion'] === 'propuesta') {
                                        echo htmlspecialchars($fila['descripcion_propuesta']);
                                    } elseif ($fila['tipoDonacion'] === 'especie') {
                                        echo htmlspecialchars($fila['descripcion_especie']) . " (Cantidad: " . htmlspecialchars($fila['cantidad_especie']) . ")";
                                    } else {
                                        echo "Donación monetaria.";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has realizado donaciones todavía.</p>
        <?php endif; ?>
    </section>
</div>

<?php include_once("vista/pie.html"); ?>
