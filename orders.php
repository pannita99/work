<?php
session_start();
require 'inc/config.php';
require 'inc/functions.php';
requireLogin();
$u = currentUser();
include 'inc/header.php';
if($u['role']==='seller'){
  // seller sees orders for their products
  $stmt = $pdo->prepare("SELECT o.* FROM orders o JOIN order_items oi ON o.id=oi.order_id JOIN products p ON oi.product_id=p.id WHERE p.seller_id = ? GROUP BY o.id ORDER BY o.created_at DESC");
  $stmt->execute([$u['id']]);
} elseif($u['role']==='admin'){
  $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
} else {
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");
  $stmt->execute([$u['id']]);
}
$orders = $stmt->fetchAll();
?>
<main class="container mx-auto p-4">
  <h2 class="text-2xl">Orders</h2>
  <?php foreach($orders as $o): ?>
    <div class="border p-3 mb-2">
      <strong>Order #<?= $o['id'] ?></strong> - <?= htmlspecialchars($o['status']) ?> - <?= $o['created_at'] ?>
      <div>
        <?php
          $stmt2 = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
          $stmt2->execute([$o['id']]);
          $its = $stmt2->fetchAll();
          foreach($its as $it) echo "<div>{$it['name']} x {$it['quantity']} = ".number_format($it['price']*$it['quantity'],2)."</div>";
        ?>
      </div>
    </div>
  <?php endforeach; ?>
</main>
<?php include 'inc/footer.php'; ?>