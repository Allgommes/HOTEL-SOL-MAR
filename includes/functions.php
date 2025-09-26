<?php
include '../connection.php';

$erros = [];
$reserva = null;

// Obter o ID da reserva a ser editada
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Buscar os dados da reserva
    $query = "SELECT * FROM Reservas WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $reserva = mysqli_fetch_assoc($result);
    } else {
        header("Location: ../index.php?message=Reserva não encontrada.");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}

// Processar o formulário quando for submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = mysqli_real_escape_string($conn, $_POST['cliente']);
    $quarto = intval($_POST['quarto']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $estado = $_POST['estado'];
    
    // Validar campos
    if (empty($cliente)) {
        $erros[] = "O nome do cliente é obrigatório.";
    }
    
    if (empty($quarto) || $quarto <= 0) {
        $erros[] = "O número do quarto deve ser um valor positivo.";
    }
    
    if (empty($checkin)) {
        $erros[] = "A data de check-in é obrigatória.";
    }
    
    if (empty($checkout)) {
        $erros[] = "A data de check-out é obrigatória.";
    }
    
    if (!empty($checkin) && !empty($checkout) && $checkin >= $checkout) {
        $erros[] = "A data de check-out deve ser posterior à data de check-in.";
    }
    
    // Se não houver erros, atualizar na base de dados
    if (empty($erros)) {
        $query = "UPDATE Reservas SET cliente='$cliente', quarto=$quarto, checkin='$checkin', checkout='$checkout', estado='$estado' WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            header("Location: ../index.php?message=Reserva atualizada com sucesso.");
            exit();
        } else {
            $erros[] = "Erro ao atualizar reserva: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva - Hotel Sol&Mar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border: none;
            border-radius: 10px;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Reserva #<?php echo $reserva['id']; ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($erros)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($erros as $erro): ?>
                                    <p class="mb-0"><?php echo $erro; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Nome do Cliente</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" required 
                                       value="<?php echo isset($_POST['cliente']) ? htmlspecialchars($_POST['cliente']) : htmlspecialchars($reserva['cliente']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="quarto" class="form-label">Número do Quarto</label>
                                <input type="number" class="form-control" id="quarto" name="quarto" min="1" required 
                                       value="<?php echo isset($_POST['quarto']) ? htmlspecialchars($_POST['quarto']) : $reserva['quarto']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="checkin" class="form-label">Data de Check-in</label>
                                <input type="date" class="form-control" id="checkin" name="checkin" required 
                                       value="<?php echo isset($_POST['checkin']) ? htmlspecialchars($_POST['checkin']) : $reserva['checkin']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="checkout" class="form-label">Data de Check-out</label>
                                <input type="date" class="form-control" id="checkout" name="checkout" required 
                                       value="<?php echo isset($_POST['checkout']) ? htmlspecialchars($_POST['checkout']) : $reserva['checkout']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="Ativa" <?php echo (isset($_POST['estado']) ? ($_POST['estado'] == 'Ativa' ? 'selected' : '') : ($reserva['estado'] == 'Ativa' ? 'selected' : '')); ?>>Ativa</option>
                                    <option value="Cancelada" <?php echo (isset($_POST['estado']) ? ($_POST['estado'] == 'Cancelada' ? 'selected' : '') : ($reserva['estado'] == 'Cancelada' ? 'selected' : '')); ?>>Cancelada</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="../index.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Definir a data mínima para os campos de data como hoje
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkin').setAttribute('min', today);
            document.getElementById('checkout').setAttribute('min', today);
            
            // Quando a data de check-in for alterada, atualizar a data mínima do check-out
            document.getElementById('checkin').addEventListener('change', function() {
                const checkinDate = this.value;
                document.getElementById('checkout').setAttribute('min', checkinDate);
                
                // Se a data de check-out for anterior à nova data de check-in, limpar o campo
                if (document.getElementById('checkout').value < checkinDate) {
                    document.getElementById('checkout').value = '';
                }
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>