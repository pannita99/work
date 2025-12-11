<?php
if(session_status()===PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Winter Shop</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{ --maroon:#800000; }
    .bg-maroon{ background-color:var(--maroon); }
    .text-maroon{ color:var(--maroon); }
    .btn{ padding:0.4rem 0.7rem; border:1px solid #ddd; border-radius:6px; }
  </style>
</head>
<body class="bg-gray-100" style="font-family: 'Kanit', sans-serif;">
<header class="bg-white shadow p-4">
  <div class="container mx-auto flex justify-between items-center">
    <a href="index.php" class="font-bold text-xl text-maroon">Winter Shop</a>
    <nav class="flex items-center gap-3">
      <a href="index.php">Home</a>
      <?php if(isLoggedIn()): $u = currentUser(); ?>
        <span>สวัสดี, <?= htmlspecialchars($u['name']) ?></span>
        <?php if($u['role']==='seller'): ?><a href="seller/dashboard.php">Seller Dashboard</a><?php endif; ?>
        <?php if($u['role']==='admin'): ?><a href="admin/dashboard.php">Admin</a><?php endif; ?>
        <a href="orders.php">Orders</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
