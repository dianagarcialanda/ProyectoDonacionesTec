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
if (!$acceso->conectar()) {
    die("Error de conexión con la base de datos.");
}

try {
    $pdo = $acceso->getPDO();

    // 1. Top Donantes
    $sqlTopDonantes = "SELECT u.nombre, u.correo,
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
        LIMIT 10";
    $stmtTop = $pdo->query($sqlTopDonantes);
    $resTop = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

    // 2. Últimos usuarios
    $sqlUltimos = "SELECT nombre, correo, idUsuario 
                   FROM usuarios 
                   ORDER BY idUsuario DESC 
                   LIMIT 10";
    $stmtUltimos = $pdo->query($sqlUltimos);
    $resUltimos = $stmtUltimos->fetchAll(PDO::FETCH_ASSOC);

    // 3. Usuarios por mes
    $sqlUsuariosPorMes = "SELECT 
        DATE_FORMAT(fechaRegistro, '%Y-%m') AS mes,
        COUNT(*) AS total
        FROM usuarios
        GROUP BY mes
        ORDER BY mes";
    $stmtUsuarios = $pdo->query($sqlUsuariosPorMes);
    $resUsuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    $meses = [];
    $totales = [];

    foreach ($resUsuarios as $row) {
        $meses[] = $row['mes'];
        $totales[] = $row['total'];
    }

} catch (Exception $e) {
    die("Error al obtener estadísticas: " . $e->getMessage());
}
?>

<div style="display: flex;">

    <?php include_once("vista/aside.php"); ?>
    
    <head>
        <meta charset="UTF-8">
        <title>Estadísticas</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <section class="principal">
        <h2>🏆 Máximos Donantes</h2>
        <div class="tabla-scroll">
            <table border="1" cellpadding="5" class="donantes">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Monetario</th>
                    <th>Productos</th>
                    <th>Proyectos</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($resTop as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row["nombre"]) ?></td>
                    <td><?= htmlspecialchars($row["correo"]) ?></td>
                    <td>$<?= number_format($row["total_monetario"], 2) ?></td>
                    <td><?= htmlspecialchars($row["total_especie"]) ?></td>
                    <td><?= htmlspecialchars($row["total_proyectos"]) ?></td>
                    <td><strong><?= htmlspecialchars($row["total_general"]) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br><br>

        <h2>🆕 Últimos Usuarios Registrados</h2>
        <div class="tabla-scroll">
            <table border="1" cellpadding="5" class="donantes">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                </tr>
                <?php foreach ($resUltimos as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row["nombre"]) ?></td>
                    <td><?= htmlspecialchars($row["correo"]) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <h2>📈 Crecimiento de Usuarios Registrados</h2>
        <canvas id="graficaStats"></canvas>
        <script>
            window.mesesUsuarios = <?= json_encode($meses) ?>;
            window.totalesUsuarios = <?= json_encode($totales) ?>;
        </script>
    </section>

    <script src="controlador/graficaStats.js"></script>

</div>

<?php include_once("vista/pie.html"); ?>
