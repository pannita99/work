<?php
session_start();
require 'inc/config.php';
require 'inc/functions.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $role = $_POST['role'] ?? 'user';
  // simple validation
  if(!$name || !$email || !$password){ $err='Please fill all fields'; }
  else {
    $pw = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role,created_at) VALUES (?,?,?,?,NOW())");
    try{
      $stmt->execute([$name,$email,$pw,$role]);
      header('Location: login.php'); exit;
    }catch(Exception $e){ $err='Registration failed: '.$e->getMessage(); }
  }
}
include 'inc/header.php';
?>
<main class="container mx-auto p-4">
  <h2 class="text-2xl">Register</h2>
  <?php if($err): ?><p class="text-red-600"><?= htmlspecialchars($err) ?></p><?php endif; ?>
  <form method="post" class="max-w-md">
    <label>Name</label><input name="name" class="w-full border p-2 mb-2">
    <label>Email</label><input name="email" type="email" class="w-full border p-2 mb-2">
    <label>Password</label><input name="password" type="password" class="w-full border p-2 mb-2">
    <label>Role</label>
    <select name="role" class="w-full border p-2 mb-2">
      <option value="user">User</option>
      <option value="seller">Seller</option>
    </select>
    <button class="bg-maroon text-white px-4 py-2 rounded">Register</button>
  </form>
</main>
<?php include 'inc/footer.php'; ?>