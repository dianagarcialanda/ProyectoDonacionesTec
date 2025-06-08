<!--
/*************************************************************/
/* Archivo:  index.php
 * Objetivo: página inicial de manejo de catálogo,
 *           incluye manejo de sesiones y plantillas
 * Autor:
 *************************************************************/ -->
<?php
include_once("vista/encabezado.html");
/*include_once("menu.php");*/
include_once("modelo/Usuario.php"); // Ajusta la ruta si es necesario

$rol = "";
if (isset($_SESSION["usuario"])) {
    $usuario = unserialize($_SESSION["usuario"]);
    $rol = $usuario->getRol(); // Asegúrate de que este método exista
}
?>

<div class="login-container">
    <section class="login-section">
        <img class="login-logo" src="img/mural.png" alt="Logo DonacionesTec">
        <h2 class="login-title">Iniciar Sesión</h2>
        
        <form class="login-form" id="frm" method="post" action="login.php">
            <div class="input-group">
                <label class="input-label" for="txtCve">Correo electrónico</label>
                <input class="login-input" type="text" name="txtCve" id="txtCve" required placeholder="tucorreo@ejemplo.com"/>
            </div>
            
            <div class="input-group">
                <label class="input-label" for="txtPwd">Contraseña</label>
                <input class="login-input" type="password" name="txtPwd" id="txtPwd" required placeholder="Ingresa tu contraseña"/>
            </div>
            
            <input class="login-button" type="submit" value="Ingresar"/>
            
        </form>

        <form method="post" action="index.php">
        <button class="login-button" type="submit">Regresar</button>
        </form>
        
        <p class="login-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </section>
</div>

<?php
include_once("vista/pie.html");
?>