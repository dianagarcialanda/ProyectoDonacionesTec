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
$rol = $oUsu->getRol();

if ($rol !== "usuario") {
    echo "<h2>No tienes permisos para acceder a esta página.</h2>";
    exit;
}

// resultados por tipo de donación
$monetarias = obtenerDonacionesPorTipo("monetaria");
$especie = obtenerDonacionesPorTipo("especie");
$propuestas = obtenerDonacionesPorTipo("propuesta");
?>


<div style="display: flex;">
    <?php include_once("vista/aside.php"); ?>
    <head>
        <meta charset="UTF-8">
        <title>Donaciones</title>
        
    </head>
    <section class="principal">
        <h1>Donacion Monetarias </h1>
        <input type="text" id="buscador3" placeholder="Buscar propuesta..." style="margin-bottom: 10px; padding: 5px; width: 100%; max-width: 300px;">

        <?php if ($propuestas->num_rows > 0): ?>
            <div class="tabla-scroll">
                <table class="tabla3">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $propuestas->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                            <td>
                                <a class="donar" 
                                data-nombre="<?= htmlspecialchars($fila['nombre']) ?>" 
                                data-descripcion="<?= htmlspecialchars($fila['descripcion']) ?>" 
                                data-id="<?= $fila['idUsuario'] ?>">Donar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No hay propuestas registradas.</p>
        <?php endif; ?>

        <!-- Botón de donación general -->
        <div style="margin: 20px 0;">
            <button id="donacionGeneralBtn">Donacion General</button>
        </div>

        <!-- Modal -->
        <div id="modalDonacion" style="display: none; position: fixed; top: 20%; left: 30%; background: #fff; padding: 20px; border: 1px solid #ccc;">
            <h3 id="tituloModal">Realizar Donación</h3>
            <p id="descripcionProyecto"></p>
            
            <a id="generarDonacionBtn">Generar Ficha</a>
            <button onclick="cerrarModal()">Cerrar</button>
        </div>

        <br><br>
        
        <h1 id="donacionesEspecie">Donaciones en Especie</h1>
        
        <div style="margin: 20px 0;">
            <button id="donacionEspecieBtn">Donar en Especie</button>
        </div>

         <h1 id="donacionesPropuesta">Donaciones un Proyecto</h1>
        
        <div style="margin: 20px 0;">
            <button id="donacionPropuestaBtn">Donar un proyecto</button>
        </div>
        

        <!-- Modal Especie -->
        <div id="modalDonacionEspecie" style="display: none; position: fixed; top: 20%; left: 30%; background: #fff; padding: 20px; border: 1px solid #ccc; z-index: 9999; max-width: 500px; width: 90%;">
            <h3 id="tituloModalEspecie">Realizar Donación en Especie</h3>
            <p id="descripcionProyectoEspecie"></p> <!-- opcional, si estás mostrando algún texto descriptivo -->

            <form id="formDonacionEspecie" action="generarDonacionEspecie.php" method="POST" enctype="multipart/form-data">
                <label for="descripcionEspecie">Descripción:</label><br>
                <textarea name="descripcion" id="descripcionEspecie" rows="3" required></textarea><br><br>

                <label for="cantidadEspecie">Cantidad:</label><br>
                <input type="number" name="cantidad" id="cantidadEspecie" required><br><br>

                <label for="estadoEspecie">Estado:</label><br>
                <select name="estado" id="estadoEspecie" required>
                    <option value="">Selecciona el estado</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="usado">Usado</option>
                    <option value="otro">Otro</option>
                </select><br><br>

                <label for="foto">Foto:</label><br>
                <input type="file" name="foto" id="foto" accept="image/*"><br>
                <div id="contenedorPreview" style="display: flex; justify-content: center; align-items: center; margin-top: 10px;">
                    <img id="previewImagen" src="" alt="Vista previa" style="max-width: 200px; display: none;">
                </div>
                <!-- El comprobante se genera automáticamente cuando el estado de la donación cambie a 'aprobado', no se incluye aquí -->
                <button type="submit" id="generarDonacionBtnEspecie">Generar Ficha</button>
                <button type="button" onclick="cerrarModal2()">Cerrar</button>
            </form>
        </div>

        <!-- Modal de Donación por Propuesta -->
        <div id="modalDonacionPropuesta" style="display:none; position:fixed; top:20%; left:30%; width:40%; background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
            <h3 id="tituloModalPropuesta">Realizar Donación Propuesta</h3>
            <p id="descripcionProyectoPropuesta"></p> <!-- opcional, si estás mostrando algún texto descriptivo -->


            <form id="formDonacionPropuesta" action="generarDonacionPropuesta.php" method="POST" enctype="multipart/form-data">
                <label for="descripcionPropuesta">Descripción:</label><br>
                <textarea name="descripcion" id="descripcionPropuesta" rows="3" required></textarea><br><br>

               

                <label for="archivo">Archivo:</label><br>
                <input type="file" name="archivo" id="archivo" accept="file/*"><br>
                <div id="contenedorPreview" style="display: flex; justify-content: center; align-items: center; margin-top: 10px;">
                    <img id="previewDocument" src="" alt="Vista previa" style="max-width: 200px; display: none;">
                </div>
                <!-- El comprobante se genera automáticamente cuando el estado de la donación cambie a 'aprobado', no se incluye aquí -->
                <button type="submit" id="generarDonacionBtnPropuesta">Generar Ficha</button>
                <button type="button" onclick="cerrarModal3()">Cerrar</button>
            </form>
        </div>



    </section>
    
    <script src="controlador/imgPop.js"></script>
    <script src="controlador/popDonarPropuesta.js"></script>
    <script src="controlador/popDonarEspecie.js"></script>
    <script src="controlador/popDonar.js"></script>
    <script src="controlador/busqueda.js"></script>
</div>

<?php include_once("vista/pie.html"); ?>
