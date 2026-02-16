<?php
session_start();
$conn = new mysqli("localhost", "root", "", "diary");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else $msg = "Incorrect password!";
    } else $msg = "User not found!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  background: url('https://i.pinimg.com/736x/2b/52/aa/2b52aab2cceb3da15c637e4dc2d0dfeb.jpg') no-repeat center center;
  background-size: cover;
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 15px;
}

.container {
  background: #fff;
  padding: 25px 20px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
}

input, button {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
  display: block;
}

button {
  background: #27ae60;
  color: #fff;
  border: none;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

button:hover {
  opacity: 0.9;
}

p.msg {
  color: red;
  font-weight: bold;
  text-align: center;
  word-wrap: break-word;
}

@media (max-width: 480px) {
  .container {
    padding: 20px 15px;
  }
  input, button {
    font-size: 0.95rem;
    padding: 10px;
  }
}
</style>
</head>
<body>
<div class="container">
<h2>Login</h2>
<?php if($msg != '') echo "<p class='msg'>$msg</p>"; ?>
<form method="POST">
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
<p style="text-align:center">Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
