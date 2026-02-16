<?php
session_start();
$conn = new mysqli("localhost", "root", "", "diary");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $msg = "Email already registered!";
        } else {
       
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPass);

            if ($stmt->execute()) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $stmt->insert_id;
                header("Location: dashboard.php");
                exit;
            } else {
                $msg = "Error: ".$conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Diary Registration</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:Arial,sans-serif;
    background: url('https://i.pinimg.com/736x/2b/52/aa/2b52aab2cceb3da15c637e4dc2d0dfeb.jpg') no-repeat center center;
    background-size: cover;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    padding:15px;
}
.container{
    width:100%;
    max-width:400px;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}
h2{
    text-align:center;
    margin-bottom:20px;
}
input,button{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:1rem;
}
button{
    background:#27ae60;
    color:white;
    border:none;
    cursor:pointer;
}
button:hover{opacity:.9;}
p.msg{text-align:center;color:red;font-weight:bold;}
</style>
</head>
<body>
<div class="container">
<h2>Create Account</h2>
<?php if(isset($msg)) echo "<p class='msg'>$msg</p>"; ?>
<form method="POST">
    <input name="name" type="text" placeholder="Full Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<p style="text-align:center">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
