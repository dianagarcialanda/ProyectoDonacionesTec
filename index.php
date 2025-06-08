<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php");
include_once("modelo/AccesoDatos.php");

// Conectar con la base de datos usando AccesoDatos
$oAccesoDatos = new AccesoDatos();
$oAccesoDatos->conectar();

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

// Ejecutar consulta una vez
$topDonantes = $oAccesoDatos->ejecutarConsulta($sqlTopDonantes);

// Separar en dos arreglos
$donantesMonetarios = $topDonantes;
$donantesEspecie = $topDonantes;

// Cerrar conexión
$oAccesoDatos->desconectar();
?>

<div style="display: flex;">
    <?php include_once("vista/aside.php"); ?>
    <head>
        <meta charset="UTF-8">
        <title>Inicio</title>
    </head>

    <section class="principal">

        <!-- Hero principal -->
        <div class="hero">
            <img class="imgPrincipal" src="img/mural.png" alt="Mural comunitario">
            <h1>DonacionesTec</h1>
            <h2>Un espacio para unir voluntades, cambiar realidades.</h2>
            <p>Gracias a ti, llevamos ayuda donde más se necesita. Únete, participa, transforma.</p>
        </div>

        <!-- Información -->
        <div class="info-donaciones">
            <h2>¿Por qué donar?</h2>
            <p>Tu apoyo permite mejorar instalaciones, brindar becas, adquirir equipo tecnológico y mucho más.</p>

            <h2>Formas de colaborar</h2>
            <ul>
                <li>📦 Donaciones en especie (útiles, libros, equipo).</li>
                <li>💳 Aportaciones monetarias seguras.</li>
                <li>🤝 Participación en eventos solidarios.</li>
            </ul>
        </div>

        <!-- Cómo funciona -->
        <div class="como-funciona">
            <h2>¿Cómo funciona?</h2>
            <ol>
                <li>Explora los proyectos activos.</li>
                <li>Elige uno que te inspire.</li>
                <li>Dona en especie, monetariamente o con una propuesta propia.</li>
            </ol>
        </div>
        <br>

        <!-- Mejores donadores monetarios -->
        <div class="maximosDonantes">
            <h1>Mejores donadores monetarios</h1>
            <div class="tabla-scroll">
                <table class="donantesIndex">
                    <tr>
                        <th>Nombre</th>
                        <th>Total monetario donado</th>
                    </tr>
                    <?php foreach ($donantesMonetarios as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["nombre"]) ?></td>
                            <td>$<?= number_format($row["total_monetario"], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <!-- Mejores donadores en especie -->
        <div class="maximosDonantes">
            <h1>Mejores donadores en especie</h1>
            <div class="tabla-scroll">
                <table class="donantesIndex">
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad de productos donados</th>
                    </tr>
                    <?php foreach ($donantesEspecie as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["nombre"]) ?></td>
                            <td><?= number_format($row["total_especie"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <!-- CTA final -->
        <div class="destacados">
            <h1>¿Listo para ayudar?</h1>
            <a href="logeo.php" class="boton-secundario">
                <img class="unete" src="img/unete.png">
            </a>
        </div>
    </section>
</div>

<?php include_once("vista/pie.html"); ?>
