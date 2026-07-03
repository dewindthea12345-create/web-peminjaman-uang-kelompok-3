<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

if (session_status() === PHP_SESSION_NONE) {
  session_name($sessionName ?? 'admin_kelompok_3_session');
  session_start();
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim((string)($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');

  if ($email !== '' && $password !== '') {
    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
   $_SESSION['user'] = [
    'id' => (int)$user['id'],
    'name' => 'Admin Kelompok 3',
    'email' => $user['email'],
    'role' => $user['role'],
];
      if ($user['role'] === 'admin') {
        // Admin sekarang memakai halaman admin template (bisa Anda ganti ke halaman admin khusus jika ada)
redirect('/adminhmd-1.0.0/html/index.html');
      }

      redirect('/adminhmd-1.0.0/entitas/dashboard.php');
    }

    $err = 'Email atau password salah.';
  } else {
    $err = 'Email dan password wajib diisi.';
  }
}

// Simple login UI (front-end only)
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin Peminjaman Uang</title>
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/vendors/bootstrap-icons/bootstrap-icons.css" />
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/css/style.css" />
    ...
    <style>
        .auth-body{
            background-image: url('/adminhmd-1.0.0/assets/images/png/bc-login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
    </style>
</head>
<body class="auth-body">
  <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
  </button>
  <main class="auth-page">
    <section class="auth-card">
      <a class="auth-brand" href="/adminhmd-1.0.0/" aria-label="Home">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
        <span>
          <strong>Admin Peminjaman Uang kelompok 3</strong>
          <small>Login untuk Admin / Entitas</small>
        </span>
      </a>

      <?php if ($err !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" class="needs-validation" novalidate>
        <div class="mb-4">
          <p class="eyebrow mb-1">Secure Access</p>
          <h1 class="h3 mb-1">Login</h1>
          <p class="text-muted mb-0">Masuk untuk mengelola pengajuan & angsuran.</p>
        </div>

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input class="form-control" id="email" name="email" type="email" required />
          <div class="invalid-feedback">Masukkan email valid.</div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Password</label>
            <a class="small fw-semibold" href="#">Lupa?</a>
          </div>
          <input class="form-control" id="password" name="password" type="password" minlength="6" required />
          <div class="invalid-feedback">Password minimal 6 karakter.</div>
        </div>

        <div class="auth-footer text-center mt-3">Belum punya akun? <a href="/adminhmd-1.0.0/register.php">Registrasi entitas</a></div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="rememberMe" />
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>

        <button class="btn btn-primary w-100" type="submit">
          <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Sign In
        </button>
      </form>
    </section>
  </main>

  <script src="/adminhmd-1.0.0/assets/js/bootstrap.bundle.min.js"></script>
  <script src="/adminhmd-1.0.0/assets/js/main.js"></script>
</body>
</html>

