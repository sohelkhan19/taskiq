<?php
include 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $email;
        $_SESSION['user_id'] = $user['id'];
        header("Location: pages/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<form method="POST">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>
