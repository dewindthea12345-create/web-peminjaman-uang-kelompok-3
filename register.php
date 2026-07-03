<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

$err = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim((string)($_POST['name'] ?? ''));
  $email = strtolower(trim((string)($_POST['email'] ?? '')));
  $password = (string)($_POST['password'] ?? '');

  if ($name === '' || $email === '' || $password === '') {
    $err = 'Nama, email, dan password wajib diisi.';
  } elseif (strlen($password) < 6) {
    $err = 'Password minimal 6 karakter.';
  } else {
    // Pastikan role entitas
    $role = 'entitas';
    $hash = password_hash($password, PASSWORD_BCRYPT);

    try {
      $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (:name,:email,:password_hash,:role)');
      $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => $hash,
        'role' => $role,
      ]);

      $ok = 'Registrasi berhasil. Silakan login.';
    } catch (PDOException $e) {
      // Duplicate email
      if (str_contains($e->getMessage(), 'Duplicate')) {
        $err = 'Email sudah digunakan.';
      } else {
        $err = 'Gagal registrasi: ' . $e->getMessage();
      }
    }
  }
}

?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Admin Peminjaman Uang</title>
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/vendors/bootstrap-icons/bootstrap-icons.css" />
  <link rel="stylesheet" href="/adminhmd-1.0.0/assets/css/style.css" />
</head>
<body class="auth-body">
  <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
  </button>

  <main class="auth-page">
    <section class="auth-card">
      <a class="auth-brand" href="/adminhmd-1.0.0/login.php" aria-label="Login">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
        <span>
          <strong>Admin Peminjaman Uang kelompok 3</strong>
          <small>Registrasi pendaftaran akun</small>
        </span>
      </a>

      <div class="auth-visual">
        <img src="/adminhmd-1.0.0/assets/images/png/new-bc.jpeg" alt="Register" />
      </div>

      <?php if ($err !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($err) ?></div>
      <?php elseif ($ok !== ''): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($ok) ?></div>
      <?php endif; ?>

      <form method="post" class="needs-validation" novalidate>
        <div class="mb-4">
          <p class="eyebrow mb-1">Buat Akun</p>
          <h1 class="h3 mb-1">Registrasi Entitas</h1>
          <p class="text-muted mb-0">Akun ini digunakan untuk mengajukan pinjaman.</p>
        </div>

        <div class="mb-3">
          <label class="form-label" for="name">Nama</label>
          <input class="form-control" id="name" name="name" type="text" required />
          <div class="invalid-feedback">Nama wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input class="form-control" id="email" name="email" type="email" required />
          <div class="invalid-feedback">Email wajib diisi.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input class="form-control" id="password" name="password" type="password" minlength="6" required />
          <div class="invalid-feedback">Password minimal 6 karakter.</div>
        </div>

        <button class="btn btn-primary w-100" type="submit">
          <i class="bi bi-person-plus" aria-hidden="true"></i> Daftar
        </button>
      </form>

      <div class="auth-footer">Sudah punya akun? <a href="/adminhmd-1.0.0/login.php">Login</a></div>
    </section>
  </main>

  <script src="/adminhmd-1.0.0/assets/js/bootstrap.bundle.min.js"></script>
  <script src="/adminhmd-1.0.0/assets/js/main.js"></script>
</body>
</html>

