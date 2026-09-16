<?php
require_once __DIR__ . '/env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Sends an email using PHPMailer and SMTP settings from .env
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email content (HTML)
 * @return bool True on success, false on failure
 */
function send_mail_smtp($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = naruto_env('SMTP_HOST', '');
        $mail->SMTPAuth   = naruto_env_bool('SMTP_AUTH', true);
        $mail->Username   = naruto_env('SMTP_USER', '');
        $mail->Password   = naruto_env('SMTP_PASS', '');
        $smtp_secure      = strtolower((string)naruto_env('SMTP_SECURE', 'tls'));
        $mail->SMTPSecure = $smtp_secure === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : ($smtp_secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : '');
        $mail->Port       = (int)naruto_env('SMTP_PORT', 587);
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom(naruto_env('MAIL_FROM_ADDRESS', ''), naruto_env('MAIL_FROM_NAME', 'Naruto RPG'));
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // For debugging purposes, you might want to log $mail->ErrorInfo
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
