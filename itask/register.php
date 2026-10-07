<?php
session_start();
require "db.php";
require "icons.php";
$error = "";
$invalid = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($name === "") $invalid[] = "name";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $invalid[] = "email";
    if (strlen($password) < 6) $invalid[] = "password";
    if ($invalid) {
        $error = "Check the highlighted fields. Enter your name, a valid email, and a password with at least 6 characters.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header("Location: login.php?registered=1");
            exit;
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) == 1062) {
                $error = "That email is already registered. Try signing in instead.";
                $invalid[] = "email";
            } else {
                $error = "Unable to create your account. Please try again.";
            }
        }
    }
}
$bad = fn($f) => in_array($f, $invalid, true) ? ' is-invalid" aria-invalid="true' : "";
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create account | I-Task</title><link rel="stylesheet" href="css/style.css"><script src="js/app.js" defer></script></head>
<body class="auth-page">
<main class="auth-shell">
  <section class="auth-card">
    <a class="brand" href="index.php"><span class="brand-mark">I</span> I-Task</a>
    <h1>Get started now</h1>
    <p class="muted">Create an account to start tracking your tasks.</p>
    <?php if ($error): ?><div class="alert error" role="alert" style="margin-top:16px"><?= icon("circle-alert") ?><span><?= htmlspecialchars($error) ?></span></div><?php endif; ?>
    <form method="post">
      <label for="name">Full name</label>
      <input id="name" name="name" placeholder="Your name" autocomplete="name" value="<?= htmlspecialchars($_POST["name"] ?? "") ?>" class="<?= $bad("name") ?>" required autofocus>
      <label for="email">Email address</label>
      <input id="email" type="email" name="email" placeholder="you@example.com" autocomplete="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" class="<?= $bad("email") ?>" required>
      <label for="password">Password</label>
      <div class="pw-wrap"><input id="password" type="password" name="password" minlength="6" placeholder="At least 6 characters" autocomplete="new-password" class="<?= $bad("password") ?>" required>
        <button class="pw-toggle" type="button" data-toggle="password" aria-pressed="false" aria-label="Show password"><span class="on"><?= icon("eye") ?></span><span class="off"><?= icon("eye-off") ?></span></button></div>
      <button class="btn full" type="submit" data-loading="Creating account…">Create account</button>
    </form>
    <p class="auth-foot">Already registered? <a href="login.php">Sign in</a></p>
  </section>
  <?php require "auth_art.php"; ?>
</main>
</body></html>
