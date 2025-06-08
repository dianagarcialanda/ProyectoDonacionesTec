<?php
require_once('modelo/consultasDonaciones.php');
require_once('modelo/AccesoDatos.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idDonacion = $_POST["idDonacion"];
    $accion = $_POST["accion"]; // 'aprobado' o 'rechazado'

    // Validación básica
    if (!in_array($accion, ["aprobado", "rechazado"])) {
        die("Acción no válida.");
    }

    $acceso = new AccesoDatos();
    if (!$acceso->conectar()) {
        die("Error de conexión a la base de datos.");
    }

    try {
        $pdo = $acceso->getPDO();
        $stmt = $pdo->prepare("UPDATE donaciones SET estado = ? WHERE idDonacion = ?");
        $stmt->execute([$accion, $idDonacion]);

        // Redireccionar
        header("Location: donaciones.php?mensaje=actualizado");
        exit;
    } catch (PDOException $e) {
        echo "Error al actualizar el estado: " . $e->getMessage();
    } finally {
        $acceso->desconectar();
    }
}
?>

