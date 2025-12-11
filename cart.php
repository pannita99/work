<?php
session_start();
require 'inc/config.php';
require 'inc/functions.php';

// Simple cart in session
if($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  if($action==='add'){
    $pid = intval($_POST['product_id']);
    $qty = max(1,intval($_POST['qty'] ?? 1));
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
    header('Location: cart.php'); exit;
  } elseif($action==='remove'){
    $pid = intval($_POST['product_id']);
    unset($_SESSION['cart'][$pid]);
    header('Location: cart.php'); exit;
  } elseif($action==='checkout'){
    // require login
    if(!isLoggedIn()){ header('Location: login.php'); exit; }
    $user = currentUser();
    $cart = $_SESSION['cart'] ?? [];
    if(empty($cart)){ $err='Cart empty'; }
    else {
      // create order
      $pdo->beginTransaction();
      $stmt = $pdo->prepare("INSERT INTO orders (user_id,total,created_at,status) VALUES (?,?,NOW(),?)");
      // compute total
      $total = 0;
      $items = [];
      $placeholders = implode(',', array_fill(0,count($cart),'?'));
      $pids = array_keys($cart);
      $stmt2 = $pdo->prepare("SELECT id,price,seller_id FROM products WHERE id IN ($placeholders)");
      $stmt2->execute($pids);
      $rows = $stmt2->fetchAll();
      foreach($rows as $r){
        $pid = $r['id']; $qty = $cart[$pid]; $total += $r['price']*$qty;
        $items[] = ['product_id'=>$pid,'qty'=>$qty,'price'=>$r['price']];
      }
      $stmt->execute([$user['id'],$total,'pending']);
      $order_id = $pdo->lastInsertId();
      $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)");
      foreach($items as $it){ $stmt_item->execute([$order_id,$it['product_id'],$it['qty'],$it['price']]); }
      $pdo->commit();
      unset($_SESSION['cart']);
      header('Location: orders.php'); exit;
    }
  }
}

include 'inc/header.php';
?>
<main class="container mx-auto p-4">
  <h2 class="text-2xl">Cart</h2>
  <?php if(!empty($_SESSION['cart'])): 
    $pids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0,count($pids),'?'));
    $stmt = $pdo->prepare("SELECT id,name,price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($pids);
    $rows = $stmt->fetchAll();
  ?>
  <table class="w-full">
    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
    <?php $total=0; foreach($rows as $r): $qty=$_SESSION['cart'][$r['id']]; $sub=$r['price']*$qty; $total+=$sub; ?>
      <tr>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=number_format($r['price'],2)?></td>
        <td><?= $qty ?></td>
        <td><?= number_format($sub,2) ?></td>
        <td>
          <form method="post"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= $r['id'] ?>"><button>Remove</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="mt-2 font-bold">Total: <?= number_format($total,2) ?> ฿</p>
  <form method="post" class="mt-2">
    <input type="hidden" name="action" value="checkout">
    <button class="bg-maroon text-white px-4 py-2 rounded">Checkout</button>
  </form>
  <?php else: ?><p>Cart empty</p><?php endif; ?>
</main>
<?php include 'inc/footer.php'; ?>