
<?php


require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailConfig(string $key, string $default = ''): string
{
    $value = $_ENV[$key] ?? getenv($key);

    return $value !== false && $value !== null
        ? (string) $value
        : $default;
}

$required = [
    'MAIL_HOST',
    'MAIL_PORT',
    'MAIL_USERNAME',
    'MAIL_PASSWORD',
    'MAIL_FROM_ADDRESS',
];

foreach ($required as $key) {
    if (mailConfig($key) === '') {
        exit("Konfigurasi {$key} belum tersedia.\n");
    }
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = mailConfig('MAIL_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = mailConfig('MAIL_USERNAME');
    $mail->Password = mailConfig('MAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int) mailConfig('MAIL_PORT', '587');

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        mailConfig('MAIL_FROM_ADDRESS'),
        mailConfig('MAIL_FROM_NAME', 'VerifyMagang')
    );

    // Kirim email percobaan ke alamat Gmail pengirim.
    $mail->addAddress(mailConfig('MAIL_USERNAME'));

    $mail->isHTML(true);
    $mail->Subject = 'Tes Email VerifyMagang';
    $mail->Body = '
        <h2>SMTP Berhasil!</h2>
        <p>Email pengujian dari aplikasi VerifyMagang berhasil dikirim.</p>
    ';
    $mail->AltBody = 'SMTP VerifyMagang berhasil mengirim email.';

    $mail->send();

    echo "Email berhasil dikirim. Silakan periksa inbox Gmail.\n";
} catch (Exception $e) {
    error_log('SMTP Test Error: ' . $mail->ErrorInfo);
    echo "Email gagal dikirim. Periksa log aplikasi untuk detailnya.\n";
}
