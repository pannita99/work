<?php
session_start();
require 'inc/config.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = $_POST['email'];
  $password = $_POST['password'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if($u && password_verify($password, $u['password'])){
    // load user into session
    $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];
    header('Location: index.php'); exit;
  } else $err='Invalid credentials';
}
include 'inc/header.php';
?>
<main class="container mx-auto p-4">
  <h2 class="text-2xl">Login</h2>
  <?php if($err): ?><p class="text-red-600"><?= htmlspecialchars($err) ?></p><?php endif; ?>
  <form method="post" class="max-w-md">
    <label>Email</label><input name="email" type="email" class="w-full border p-2 mb-2">
    <label>Password</label><input name="password" type="password" class="w-full border p-2 mb-2">
    <button class="bg-maroon text-white px-4 py-2 rounded">Login</button>
  </form>
</main>
<?php include 'inc/footer.php'; ?>