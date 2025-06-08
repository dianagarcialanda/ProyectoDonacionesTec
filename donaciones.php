<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php");
require_once("modelo/AccesoDatos.php");
require_once("modelo/consultasDonaciones.php");

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$oUsu = unserialize($_SESSION["usuario"]);
if ($oUsu->getRol() !== 'admin') {
    echo "<h2>No tienes permisos para acceder a esta página.</h2>";
    exit;
}

// Aquí sí llamamos a la función que ya está en consultasDonaciones.php
$monetarias = obtenerDonacionesPorTipo("monetaria");
$especie    = obtenerDonacionesPorTipo("especie");
$propuestas = obtenerDonacionesPorTipo("propuesta");
?>
?>

<div style="display: flex;">
<?php include_once("vista/aside.php"); ?>
<head>
    <meta charset="UTF-8">
    <title>Donaciones</title>
</head>

<section class="principal">
    <!-- Monetarias -->
    <h1>Donaciones Monetarias</h1>
    <input type="text" id="buscador1" placeholder="Buscar por nombre..." style="margin-bottom: 10px; padding: 5px; width: 100%; max-width: 300px;">

    <?php if (count($monetarias) > 0): ?>
        <div class="tabla-scroll">
            <table class="tabla1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monetarias as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['tipoDonacion']) ?></td>
                        <td>
                            <button class="ver-mas-btn" data-id="<?= $fila['idDonacion'] ?>" data-tipo="monetaria">Ver más</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay donaciones monetarias registradas.</p>
    <?php endif; ?>

    <!-- Especie -->
    <h1 id="donacionesEspecie">Donaciones en Especie</h1>
    <input type="text" id="buscador2" placeholder="Buscar por nombre..." style="margin-bottom: 10px; padding: 5px; width: 100%; max-width: 300px;">

    <?php if (count($especie) > 0): ?>
        <div class="tabla-scroll">
            <table class="tabla2">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($especie as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['tipoDonacion']) ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td>
                            <button class="ver-mas-btn" data-id="<?= $fila['idDonacion'] ?>" data-tipo="especie">Ver más</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <style>
                .modal {
                    position: fixed;
                    z-index: 9999;
                    left: 0; top: 0;
                    width: 100%; height: 100%;
                    overflow: auto;
                    background-color: rgba(0,0,0,0.5);
                }
                .modal-contenido {
                    background-color: #fff;
                    margin: 10% auto;
                    padding: 20px;
                    border-radius: 10px;
                    width: 80%;
                    max-width: 600px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                }
                .cerrar {
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }
                .cerrar:hover { color: black; }
            </style>
        </div>
    <?php else: ?>
        <p>No hay donaciones en especie registradas.</p>
    <?php endif; ?>

    <!-- Propuestas -->
    <h1 id="donacionesProyectos">Propuestas</h1>
    <input type="text" id="buscador3" placeholder="Buscar por nombre..." style="margin-bottom: 10px; padding: 5px; width: 100%; max-width: 300px;">

    <?php if (count($propuestas) > 0): ?>
        <div class="tabla-scroll">
            <table class="tabla3">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Propuesta</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propuestas as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['tipoDonacion']) ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td>
                            <button class="ver-mas-btn" data-id="<?= $fila['idDonacion'] ?>" data-tipo="propuesta">Ver más</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No hay propuestas registradas.</p>
    <?php endif; ?>

    <!-- Modal -->
    <div id="modalDetalle" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
        <button id="cerrarModal" style="float:right;">X</button>
        <div id="contenidoModal"></div>
    </div>

</section>

<script src="controlador/popDetalles.js"></script>
<script src="controlador/busqueda.js"></script>
</div>

<?php include_once("vista/pie.html"); ?>
