<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendResetPasswordEmail(
    string $recipientEmail,
    string $recipientName,
    string $resetUrl
): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
        $mail->Password = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) ($_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?: 587);

        $mail->CharSet = 'UTF-8';

        $mail->setFrom(
            $_ENV['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS'),
            $_ENV['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?: 'VerifyMagang'
        );

        $mail->addAddress($recipientEmail, $recipientName);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password VerifyMagang';

        $safeName = htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');

        $mail->Body = "
            <h2>Reset Password VerifyMagang</h2>
            <p>Halo {$safeName},</p>
            <p>Kami menerima permintaan untuk mengatur ulang password akunmu.</p>
            <p>
                <a href='{$safeUrl}'>Klik di sini untuk reset password</a>
            </p>
            <p>Tautan berlaku selama 30 menit dan hanya dapat digunakan satu kali.</p>
            <p>Jika kamu tidak meminta reset password, abaikan email ini.</p>
            <p>Salam,<br>Tim VerifyMagang</p>
        ";

        $mail->AltBody = "Halo {$recipientName}, gunakan tautan berikut untuk reset password: {$resetUrl}. Tautan berlaku selama 30 menit.";

        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
