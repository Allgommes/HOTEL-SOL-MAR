<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

$database = new Database();
$db = $database->getConnection();

// Buscar reservas
$query = "SELECT r.*, f.nome as funcionario_nome 
          FROM reservas r 
          LEFT JOIN funcionarios f ON r.funcionario_id = f.id 
          ORDER BY r.checkin DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestão de Reservas</h2>
    <a href="create.php" class="btn btn-success">Nova Reserva</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Reserva criada com sucesso!</div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Reserva atualizada com sucesso!</div>
<?php endif; ?>

<?php if (isset($_GET['canceled'])): ?>
    <div class="alert alert-info">Reserva cancelada com sucesso!</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Quarto</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Estado</th>
                        <th>Funcionário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservas) > 0): ?>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reserva['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($reserva['quarto']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reserva['checkin'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reserva['checkout'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $reserva['estado'] == 'Ativa' ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($reserva['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($reserva['funcionario_nome']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $reserva['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <?php if ($reserva['estado'] == 'Ativa'): ?>
                                        <a href="cancel.php?id=<?php echo $reserva['id']; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">Cancelar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma reserva encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>