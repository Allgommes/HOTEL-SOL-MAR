<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function enviar_email($destinatario, $nome_destinatario, $assunto, $mensagem)
{
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.sapo.pt';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gomesme_meting@sapo.pt';
        $mail->Password   = '5Pz6jqfOr$3C';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Remetente e destinatário
        $mail->setFrom('gomesme_meting@sapo.pt', 'Hotel Sol&Mar');
        $mail->addAddress($destinatario, $nome_destinatario); // Corrigido - removidas as aspas simples

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $assunto; // Corrigido - removidas as aspas simples
        $mail->Body    = $mensagem; // Corrigido - removidas as aspas simples

        return $mail->send();
    } catch (Exception $e) {
        error_log("Erro ao enviar email: " . $mail->ErrorInfo);
        return false;
    }
}

// enviar_email("$destinatario", "$nome_destinatario", "$assunto", "$mensagem"); // teste

// Função específica para notificação de login
function notificar_login_admin($nome_funcionario, $email_funcionario)
{
    $assunto = "Notificação de Login - Hotel Sol&Mar";
    $mensagem = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .footer { text-align: center; padding: 10px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Hotel Sol&Mar - Sistema de Gestão</h2>
            </div>
            <div class='content'>
                <h3>Notificação de Login</h3>
                <p>Um funcionário acabou de fazer login no sistema:</p>
                <ul>
                    <li><strong>Nome:</strong> {$nome_funcionario}</li>
                    <li><strong>Email:</strong> {$email_funcionario}</li>
                    <li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>
                </ul>
                <p>Se não reconhece esta atividade, por favor contacte a administração.</p>
            </div>
            <div class='footer'>
                <p>Este é um email automático, por favor não responda.</p>
            </div>
        </div>
    </body>
    </html>";

    return enviar_email('gomesme_meting@sapo.pt', 'Administrador', $assunto, $mensagem);
}

// Função para notificação de cancelamento de reserva
function notificar_cancelamento_reserva($dados_reserva)
{
    $assunto = "Cancelamento de Reserva - Hotel Sol&Mar";
    $mensagem = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .footer { text-align: center; padding: 10px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Hotel Sol&Mar - Cancelamento de Reserva</h2>
            </div>
            <div class='content'>
                <h3>Reserva Cancelada</h3>
                <p>Uma reserva foi cancelada no sistema:</p>
                <ul>
                    <li><strong>Cliente:</strong> {$dados_reserva['cliente']}</li>
                    <li><strong>Quarto:</strong> {$dados_reserva['quarto']}</li>
                    <li><strong>Check-in:</strong> " . date('d/m/Y', strtotime($dados_reserva['checkin'])) . "</li>
                    <li><strong>Check-out:</strong> " . date('d/m/Y', strtotime($dados_reserva['checkout'])) . "</li>
                    <li><strong>Cancelado por:</strong> {$dados_reserva['funcionario_nome']}</li>
                    <li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>
                </ul>
            </div>
            <div class='footer'>
                <p>Este é um email automático, por favor não responda.</p>
            </div>
        </div>
    </body>
    </html>";

    return enviar_email('gomesme_meting@sapo.pt', 'Administrador', $assunto, $mensagem);
}

// Função para confirmação de registo de funcionário
function enviar_confirmacao_registro($email_funcionario, $nome_funcionario)
{
    $assunto = "Bem-vindo ao Hotel Sol&Mar";
    $mensagem = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #28a745; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .footer { text-align: center; padding: 10px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Hotel Sol&Mar - Bem-vindo!</h2>
            </div>
            <div class='content'>
                <h3>Olá, {$nome_funcionario}!</h3>
                <p>O seu registo no sistema de gestão do Hotel Sol&Mar foi realizado com sucesso.</p>
                <p><strong>Detalhes da conta:</strong></p>
                <ul>
                    <li><strong>Nome:</strong> {$nome_funcionario}</li>
                    <li><strong>Email:</strong> {$email_funcionario}</li>
                    <li><strong>Data de registo:</strong> " . date('d/m/Y') . "</li>
                </ul>
                <p>Já pode fazer login no sistema usando as suas credenciais.</p>
                <p><a href='http://localhost/hotel-sol-mar/login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Fazer Login</a></p>
            </div>
            <div class='footer'>
                <p>Este é um email automático, por favor não responda.</p>
            </div>
        </div>
    </body>
    </html>";

    return enviar_email($email_funcionario, $nome_funcionario, $assunto, $mensagem);
}
