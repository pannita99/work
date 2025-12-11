<?php
session_start();
require '../inc/config.php';
require '../inc/functions.php';
if(!isLoggedIn() || !isSeller()) { header('Location: ../login.php'); exit; }
$u = currentUser();
include '../inc/header.php';

// simple seller product list
$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id=? ORDER BY created_at DESC");
$stmt->execute([$u['id']]);
$products = $stmt->fetchAll();
?>
<main class="container mx-auto p-4">
  <h2 class="text-2xl">Seller Dashboard</h2>
  <a href="product_edit.php" class="bg-maroon text-white px-3 py-1 rounded">Add Product</a>
  <h3 class="mt-4">Your Products</h3>
  <?php foreach($products as $p): ?>
    <div class="border p-2 mb-2"><?=htmlspecialchars($p['name'])?> - <?=number_format($p['price'],2)?> ฿ - Approved: <?= $p['approved'] ? 'Yes' : 'No' ?></div>
  <?php endforeach; ?>
</main>
<?php include '../inc/footer.php'; ?>