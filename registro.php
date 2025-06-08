<?php include_once("vista/encabezado.html"); ?>

<div class="registro-container">
    <section class="registro-section">
        <img class="registro-logo" src="img/mural.png" alt="Logo DonacionesTec">
        <h2 class="registro-title">Registro de Usuario</h2>
        
        <form class="registro-form" id="frm" method="post" action="controlador/procesarRegistro.php">
            <div class="input-group2">
                <label class="input-label2" for="nombre">Nombre completo:</label>
                <input class="registro-input" type="text" name="nombre" required placeholder="Fulanito"/>
            </div>
            
            <div class="input-group2">
                <label class="input-label2" for="correo">Correo electrónico:</label>
                <input class="registro-input" type="email" name="correo" required placeholder="tucorreo@ejemplo.com"/>
            </div>

            <div class="input-group2">
                <label class="input-label2" for="telefono">Telefono:</label>
                <input class="registro-input" type="tel" name="telefono" required placeholder="Numero telefonico"/>
            </div>

            <div class="input-group2">
                <label class="input-label2" for="direccion">Direccion:</label>
                <input class="registro-input" type="text" name="direccion" required placeholder="Calle ejemplo 123"/>
            </div>

            <div class="input-group2">
                <label class="input-label2" for="fechaNac">Fecha de nacimiento:</label>
                <input class="registro-input" type="date" name="fechaNac" required placeholder="dd/mm/aaaa"/>
            </div>

            <div class="input-group2">
                <label class="input-label2" for="password">Contraseña:</label>
                <input class="registro-input" type="password" name="password" required placeholder="Crea tu password"/>
            </div>

            <div class="input-group2">
                <label class="input-label2" for="confirmar">Confirmar contraseña:</label>
                <input class="registro-input" type="password" name="confirmar" required placeholder="Confirma tu password"/>
            </div>

            
            <input class="registro-button" type="submit" value="Registrarse"/>
            
        </form>

        <form method="post" action="index.php">
        <button class="registro-button" type="submit">Regresar</button>
        </form>
        
    </section>
</div>


<?php include_once("vista/pie.html"); ?>
