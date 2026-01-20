<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return isset($_SESSION["user_id"]);
}

function role(): string {
    return $_SESSION["role"] ?? "Guest";
}

function require_login(): void {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function require_role(array $allowed): void {
    require_login();
    if (!in_array(role(), $allowed, true)) {
        http_response_code(403);
        echo "אין לך הרשאה לצפות בעמוד זה.";
        exit;
    }
}

function current_role(): string {
    return role();  
}
