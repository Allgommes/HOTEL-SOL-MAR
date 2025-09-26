<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: reservas/index.php");
    exit();
}

$error = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();

    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email e password são obrigatórios.";
    } else {
        $query = "SELECT id, nome, senha, ativo FROM funcionarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 1) {
            $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($funcionario['ativo'] && password_verify($password, $funcionario['senha'])) {
                $_SESSION['funcionario_id'] = $funcionario['id'];
                $_SESSION['funcionario_nome'] = $funcionario['nome'];
                $_SESSION['funcionario_email'] = $email;

                // Atualizar último login
                $update_query = "UPDATE funcionarios SET ultimo_login = NOW() WHERE id = ?";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->execute([$funcionario['id']]);

                header("Location: reservas/index.php");
                exit();
            } else {
                $error = "Credenciais inválidas ou conta inativa.";
            }
        } else {
            $error = "Credenciais inválidas.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Login</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="register.php">Não tem conta? Registe-se</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: reservas/index.php");
    exit();
}

$error = '';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();

    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email e password são obrigatórios.";
    } else {
        $query = "SELECT id, nome, senha, ativo FROM funcionarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 1) {
            $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Para teste, use a password 'password' para o admin
            if ($funcionario['ativo'] && password_verify($password, $funcionario['senha'])) {
                // Iniciar sessão se ainda não estiver iniciada
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['funcionario_id'] = $funcionario['id'];
                $_SESSION['funcionario_nome'] = $funcionario['nome'];
                $_SESSION['funcionario_email'] = $email;

                // Atualizar último login
                $update_query = "UPDATE funcionarios SET ultimo_login = NOW() WHERE id = ?";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->execute([$funcionario['id']]);

                header("Location: reservas/index.php");
                exit();
            } else {
                $error = "Credenciais inválidas ou conta inativa.";
            }
        } else {
            $error = "Credenciais inválidas.";
        }
    }
}
?>

<!-- Resto do código do login.php permanece igual -->