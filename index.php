<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: reservas/index.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Hotel Sol&Mar</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4>Bem-vindo ao Sistema de Gestão</h4>
                    <p class="text-muted">Faça login para aceder ao sistema</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="login.php" class="btn btn-primary btn-lg">Login</a>
                    <a href="register.php" class="btn btn-outline-secondary btn-lg">Registo</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>