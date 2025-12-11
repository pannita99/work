<?php
session_start();
require 'inc/config.php';
require 'inc/functions.php';
include 'inc/header.php';

$sql = "
SELECT 
    p.id, 
    p.name, 
    p.image, 
    p.price, 
    s.name AS shop_name 
FROM products p 
JOIN sellers s ON p.seller_id = s.id 
WHERE p.approved = 1 
ORDER BY p.created_at DESC
";

$stmt = $pdo->prepare($sql);

if ($stmt->execute()) {
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    error_log("Error fetching products: " . print_r($stmt->errorInfo(), true));
    $products = [];
}
?> 

<main class="container mx-auto p-4">
  <h1 class="text-3xl font-bold mb-4" style="font-family: 'Kanit', sans-serif; color:#800000;">ร้านเสื้อกันหนาวออนไลน์</h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    <?php foreach($products as $p): ?>
      <div class="border rounded p-4 shadow">
        <img src="<?= htmlspecialchars($p['image'] ?: 'assets/images/placeholder.png') ?>" alt="" class="w-full h-48 object-cover mb-2">
        <h2 class="text-xl font-semibold"><?= htmlspecialchars($p['name']) ?></h2>
        <p class="text-sm text-gray-600"><?= htmlspecialchars($p['shop_name']) ?></p>
        <p class="font-bold mt-2"><?= number_format($p['price'],2) ?> ฿</p>
        <div class="mt-3 flex gap-2">
          <button onclick="openQuickView(<?= $p['id'] ?>)" class="btn">Quick View</button>
          <form method="post" action="cart.php" class="inline">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
            <button class="btn bg-maroon text-white px-3 py-1 rounded">Add to Cart</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- Quick view modal placeholder -->
<div id="quickView" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white p-4 rounded w-11/12 md:w-2/3" id="quickViewContent"></div>
</div>

<script>
function openQuickView(id){
  fetch('product_quick.php?id=' + id)
    .then(r => r.text())
    .then(html => {
      document.getElementById('quickViewContent').innerHTML = html;
      document.getElementById('quickView').classList.remove('hidden');
    });
}
function closeQuick(){
  document.getElementById('quickView').classList.add('hidden');
}
</script>

<?php include 'inc/footer.php'; ?>
