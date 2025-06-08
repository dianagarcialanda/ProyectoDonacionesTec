<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php");
include_once("AccesoDatos.php");  // Incluir la clase

// Instancia y conexión
$accesoDatos = new AccesoDatos();
$accesoDatos->conectar();

$rol = "";
if (isset($_SESSION["usuario"])) {
    $usuario = unserialize($_SESSION["usuario"]);
    $nombre = $usuario->getNombre();
    $rol = $usuario->getRol();
}

// Consulta para donaciones
$sqlDonaciones = "
    SELECT d.idDonacion, d.tipoDonacion, d.fecha, d.estado,
        dp.descripcion AS descripcion_propuesta,
        de.descripcion AS descripcion_especie,
        de.cantidad AS cantidad_especie
    FROM donaciones d
    LEFT JOIN donacionespropuesta dp ON d.idDonacion = dp.idDonacion
    LEFT JOIN donacionesespecie de ON d.idDonacion = de.idDonacion
    ORDER BY d.fecha DESC
";

$donaciones = $accesoDatos->ejecutarConsulta($sqlDonaciones);

// Consulta para top donantes
$sqlTopDonantes = "
    SELECT u.nombre, u.correo,
        COALESCE(SUM(dm.monto), 0) AS total_monetario,
        COALESCE(SUM(de.cantidad), 0) AS total_especie,
        COALESCE(COUNT(DISTINCT dp.idDonacion), 0) AS total_proyectos,
        (
            COALESCE(SUM(dm.monto), 0) + 
            COALESCE(SUM(de.cantidad), 0) + 
            COALESCE(COUNT(DISTINCT dp.idDonacion), 0)
        ) AS total_general
    FROM usuarios u
    LEFT JOIN donaciones d ON u.idUsuario = d.idUsuario
    LEFT JOIN donacionesmonetarias dm ON d.idDonacion = dm.idDonacion
    LEFT JOIN donacionesespecie de ON d.idDonacion = de.idDonacion
    LEFT JOIN donacionespropuesta dp ON d.idDonacion = dp.idDonacion
    GROUP BY u.idUsuario
    ORDER BY total_general DESC
    LIMIT 10
";

$topDonantes = $accesoDatos->ejecutarConsulta($sqlTopDonantes);

// Si necesitas dos veces el resultado para las tablas monetarios y especie,
// mejor duplicar el arreglo o hacer una consulta específica para cada uno,
// porque ejecutarConsulta devuelve un array, no un objeto con cursor.

$accesoDatos->desconectar();

?>

<!-- El resto de tu HTML permanece igual, solo que ahora recorres los arrays $donaciones y $topDonantes -->

<?php if ($rol === "admin"): ?>
    <!-- Tabla historial con $donaciones -->
    <?php if ($donaciones): ?>
        <table class="tabla1">
            <thead>...</thead>
            <tbody>
                <?php foreach ($donaciones as $fila): ?>
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
        <p>No hay donaciones para mostrar.</p>
    <?php endif; ?>
<?php else: ?>
    <!-- Para usuarios normales, usar $topDonantes para las tablas -->
    <table class="donantesIndex">
        <tr>
            <th>Nombre</th>
            <th>Total monetario donado</th>
        </tr>
        <?php foreach ($topDonantes as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row["nombre"]) ?></td>
            <td>$<?= number_format($row["total_monetario"], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include_once("vista/pie.html"); ?>
