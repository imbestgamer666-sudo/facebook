# 文件名: secure_functions.php (通用安全函数)
<?php
// 防止SQL注入和XSS, 过滤输入数据
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// 检测常见的注入payload
function detect_injection($input) {
    $patterns = [
        '/\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|EXEC|EVAL)\b/i',
        '/\b(OR|AND)\s+.*=.*/i',
        '/\b(SCRIPT|ALERT|ONLOAD|JAVASCRIPT)\b/i',
        '/[\'";<>]/'
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            return true;
        }
    }
    return false;
}

// 生成CSRF令牌
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 验证CSRF令牌
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>