<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once("modelo/Usuario.php");
require_once("modelo/AccesoDatos.php");

$oUsu = unserialize($_SESSION["usuario"]);
$idUsuario = $oUsu->getIdUsuario();

// Validar entrada
$descripcion = $_POST['descripcion'] ?? '';
if (empty($descripcion)) {
    die("La descripción es obligatoria para una propuesta.");
}

// 1. Conectar a la base de datos
$oAD = new AccesoDatos();
if (!$oAD->conectar()) {
    die("Error de conexión a la base de datos.");
}
$pdo = $oAD->getPDO(); // Asegúrate de que tu clase AccesoDatos tenga el método getPDO()

// 2. Insertar en donaciones y donacionespropuesta dentro de una transacción
try {
    $fecha = date('Y-m-d');
    $tipoDonacion = 'propuesta';
    $estadoDonacion = 'pendiente';

    $pdo->beginTransaction();

    // Insertar en donaciones
    $stmt1 = $pdo->prepare("INSERT INTO donaciones (idUsuario, tipoDonacion, fecha, estado) VALUES (?, ?, ?, ?)");
    $stmt1->execute([$idUsuario, $tipoDonacion, $fecha, $estadoDonacion]);

    $idDonacion = $pdo->lastInsertId();

    // Insertar en donacionespropuesta
    $stmt2 = $pdo->prepare("INSERT INTO donacionespropuesta (idDonacion, descripcion) VALUES (?, ?)");
    $stmt2->execute([$idDonacion, $descripcion]);

    $pdo->commit();

    echo "<script>alert('Donación por propuesta registrada exitosamente.'); window.location.href='donacionesUsuario.php';</script>";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error en la transacción: " . $e->getMessage());
}
?>
