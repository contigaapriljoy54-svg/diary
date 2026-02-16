<?php
session_start();
require 'diary.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "User not found or password incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Diary Login</title>

<style>
  html, body {
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
    color: #fff;
    background: url(https://betterlyf-upload.s3.amazonaws.com/1672910170319.jpg) no-repeat center center fixed;
    background-size: cover;
  }

  body::before {
    content: "";
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.65);
    z-index: -1;
  }

  h2 {
    text-align: center;
    margin-top: 60px;
    text-shadow: 2px 2px 6px #000;
    font-size: 28px;
  }

  form {
    max-width: 400px;
    margin: 40px auto;
    background: rgba(0,0,0,0.6);
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 0 25px rgba(0,0,0,0.6);
  }

  input {
    width: 94%;
    padding: 14px;
    margin: 12px 0;
    border-radius: 10px;
    border: none;
    font-size: 15px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    outline: none;
    transition: 0.3s;
  }

  input::placeholder {
    color: #ddd;
  }

  input:focus {
    background: rgba(255,255,255,0.25);
    box-shadow: 0 0 8px #27ae60;
  }

  button {
    width: 100%;
    padding: 14px;
    margin-top: 15px;
    background: linear-gradient(135deg, #27ae60, #1e8449);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
  }

  button:hover {
    transform: scale(1.03);
    background: linear-gradient(135deg, #1e8449, #145a32);
  }

  #msg {
    text-align: center;
    margin-top: 15px;
    font-weight: bold;
  }

  #registerLink {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #fff;
    text-decoration: underline;
    cursor: pointer;
    transition: 0.3s;
  }

  #registerLink:hover {
    color: #27ae60;
  }
</style>
</head>
<body>

<h2>Login to Your Diary</h2>

<form id="loginForm">
  <input id="email" type="email" placeholder="Email" required>
  <input id="password" type="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>

<p id="msg"></p>
<span id="registerLink">Don't have an account? Register here</span>

<script>
const loginForm = document.getElementById('loginForm');
const msg = document.getElementById('msg');
const registerLink = document.getElementById('registerLink');

registerLink.onclick = () => window.location.href = 'register.html';

loginForm.onsubmit = e => {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();

  let users = JSON.parse(localStorage.getItem('users')) || [];
  const user = users.find(u => u.email === email && u.password === password);

  if (user) {
    localStorage.setItem('user', JSON.stringify(user));
    window.location.href = 'dashboard.html';
  } else {
    msg.style.color = '#ff4d4d';
    msg.innerText = 'User not found or password incorrect';
  }
};
</script>

</body>
</html>
