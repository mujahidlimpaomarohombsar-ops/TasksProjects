<?php
session_start();
require "db.php";
require "icons.php";
$error = "";
$notice = isset($_GET["registered"]) ? "Account created. Sign in to continue." : "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Incorrect email or password. Check your details and try again.";
}
$bad = $error ? ' is-invalid" aria-invalid="true' : "";
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign in | I-Task</title><link rel="stylesheet" href="css/style.css"><script src="js/app.js" defer></script></head>
<body class="auth-page">
<main class="auth-shell">
  <section class="auth-card">
    <a class="brand" href="index.php"><span class="brand-mark">I</span> I-Task</a>
    <h1>Welcome back</h1>
    <p class="muted">Sign in to manage your tasks.</p>
    <?php if ($notice): ?><div class="alert success" role="status" style="margin-top:16px"><?= icon("circle-check") ?><span><?= htmlspecialchars($notice) ?></span></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error" role="alert" style="margin-top:16px"><?= icon("circle-alert") ?><span><?= htmlspecialchars($error) ?></span></div><?php endif; ?>
    <form method="post">
      <label for="email">Email address</label>
      <input id="email" type="email" name="email" placeholder="you@example.com" autocomplete="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" class="<?= $bad ?>" required autofocus>
      <label for="password">Password</label>
      <div class="pw-wrap"><input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" class="<?= $bad ?>" required>
        <button class="pw-toggle" type="button" data-toggle="password" aria-pressed="false" aria-label="Show password"><span class="on"><?= icon("eye") ?></span><span class="off"><?= icon("eye-off") ?></span></button></div>
      <button class="btn full" type="submit" data-loading="Signing in…">Sign in</button>
    </form>
    <p class="auth-foot">No account? <a href="register.php">Create one</a></p>
  </section>
  <?php require "auth_art.php"; ?>
</main>
</body></html>
