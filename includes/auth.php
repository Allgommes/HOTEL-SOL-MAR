<?php
session_start();

function isLoggedIn()
{
    return isset($_SESSION['funcionario_id']);
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}
