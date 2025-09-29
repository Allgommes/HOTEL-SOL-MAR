<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../email_config.php';

redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Buscar dados da reserva antes de cancelar
$query = "SELECT r.*, f.nome as funcionario_nome 
          FROM reservas r 
          LEFT JOIN funcionarios f ON r.funcionario_id = f.id 
          WHERE r.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if ($reserva) {
    // Atualizar estado para Cancelada
    $update_query = "UPDATE reservas SET estado = 'Cancelada' WHERE id = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->execute([$id]);

    // ENVIAR EMAIL DE NOTIFICAÇÃO DE CANCELAMENTO
    notificar_cancelamento_reserva($reserva);
}

header("Location: index.php?canceled=1");
exit();
?>
