<?php
require 'inc/config.php';
require 'inc/functions.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, s.shop_name FROM products p JOIN sellers s ON p.seller_id=s.id WHERE p.id=?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$p){ echo "<p>Product not found</p>"; exit; }
?>
<button onclick="closeQuick()" class="float-right">Close</button>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div><img src="<?= htmlspecialchars($p['image']?:'assets/images/placeholder.png') ?>" class="w-full"></div>
  <div>
    <h2 class="text-2xl font-bold"><?= htmlspecialchars($p['name']) ?></h2>
    <p class="mt-2"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
    <p class="mt-3 font-bold"><?= number_format($p['price'],2) ?> ฿</p>
    <form method="post" action="cart.php">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
      <label>จำนวน</label>
      <input type="number" name="qty" value="1" min="1" class="border p-1 w-20">
      <button class="btn bg-maroon text-white px-3 py-1 rounded">Add to Cart</button>
    </form>
  </div>
</div>
