<?php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php");

$conn = new mysqli("localhost", "root", "", "diary");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);
    $stmt = $conn->prepare("INSERT INTO notes (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $content);
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT content, created_at FROM notes WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Diary</title>
<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: Arial, sans-serif;
  background: #f0f0f0;
  padding: 20px;
}

.container {
  background: url('https://i.pinimg.com/736x/2b/52/aa/2b52aab2cceb3da15c637e4dc2d0dfeb.jpg') no-repeat center center;
  background-size: cover;
  max-width: 650px;
  margin: auto;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

h2 {
  margin-bottom: 15px;
}

textarea {
  width: 100%;
  padding: 14px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-bottom: 12px;
  font-size: 14px;
  resize: none;
}

button {
  padding: 12px 18px;
  background: #27ae60;
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

button:hover {
  opacity: 0.9;
}

.entry {
  background: rgba(255, 255, 255, 0.9);
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 12px;
  border: 1px solid #eee;
  white-space: pre-wrap;
}

#logoutBtn {
  display: inline-block;
  float: right;
  background: #e53935;
  color: #fff;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: bold;
  margin-bottom: 20px;
  transition: all 0.3s ease;
}

#logoutBtn:hover {
  opacity: 0.9;
}

@media (max-width: 600px) {
  textarea, button, #logoutBtn {
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 10px;
  }
}
</style>
</head>
<body>
<div class="container">
<h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
<a id="logoutBtn" href="logout.php">Logout</a>
<form method="POST" style="margin-bottom:20px;">
<textarea name="content" placeholder="Write your diary entry here..." required></textarea>
<button type="submit">Add Note</button>
</form>
<h3>Your Notes</h3>
<?php while($note = $notes->fetch_assoc()): ?>
<div class="entry"><?php echo htmlspecialchars($note['content']); ?><br><small><?php echo $note['created_at']; ?></small></div>
<?php endwhile; ?>
</div>
</body>
</html>
