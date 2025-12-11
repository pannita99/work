<?php
session_start();
require '../inc/config.php';
require '../inc/functions.php';
if(!isLoggedIn() || !isSeller()) { header('Location: ../login.php'); exit; }
$u = currentUser();
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = $_POST['name']; $desc = $_POST['description']; $price = floatval($_POST['price']);
  $image = $_POST['image'] ?: 'assets/images/placeholder.png';
  $stmt = $pdo->prepare("INSERT INTO products (seller_id,name,description,price,image,approved,created_at) VALUES (?,?,?,?,?,0,NOW())");
  $stmt->execute([$u['id'],$name,$desc,$price,$image]);
  header('Location: dashboard.php'); exit;
}
include '../inc/header.php';
?>
<main class="container mx-auto p-4">
  <h2>Add Product</h2>
  <?php if($err) echo "<p class='text-red-600'>$err</p>"; ?>
  <form method="post" class="max-w-md">
    <label>Name</label><input name="name" class="w-full border p-2 mb-2">
    <label>Price</label><input name="price" class="w-full border p-2 mb-2">
    <label>Image URL</label><input name="image" class="w-full border p-2 mb-2" value="assets/images/placeholder.png">
    <label>Description</label><textarea name="description" class="w-full border p-2 mb-2"></textarea>
    <button class="bg-maroon text-white px-3 py-1 rounded">Save</button>
  </form>
</main>
<?php include '../inc/footer.php'; ?>