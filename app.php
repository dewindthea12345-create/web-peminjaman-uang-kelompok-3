<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_name($sessionName ?? 'admin_kelompok_3_session');
  session_start();
}

function db(): PDO {
  static $pdo = null;
  global $dbHost, $dbName, $dbUser, $dbPass;

  if ($pdo instanceof PDO) return $pdo;

  $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);

  return $pdo;
}

function require_login(): void {
  if (empty($_SESSION['user'])) {
    header('Location: /adminhmd-1.0.0/login.php');
    exit;
  }
}

function require_role(array $roles): void {
  $user = $_SESSION['user'] ?? null;
  $role = $user['role'] ?? null;

  if (!$role || !in_array($role, $roles, true)) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
  }
}

function redirect(string $path): void {
  header('Location: ' . $path);
  exit;
}

