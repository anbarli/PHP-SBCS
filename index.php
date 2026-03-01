<?php
// Enable strict typing / Kesin tür kontrolü etkinleştir
declare(strict_types=1);
error_reporting(E_ALL);

$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
);
ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self' https://cdn.jsdelivr.net; form-action 'self'; frame-ancestors 'none'; base-uri 'self';");

$demo_mode = true; // DEMO: Gerçek kayıt yapılmasın

function getCsrfToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function setFlashMessage(string $message): void {
    $_SESSION['flash_msg'] = $message;
}

function redirectToHome(): void {
    header('Location: index.php');
    exit;
}

function generateOrderNumber(int $length = 8): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $maxIndex = strlen($chars) - 1;
    $value = '';
    for ($i = 0; $i < $length; $i++) {
        $value .= $chars[random_int(0, $maxIndex)];
    }
    return $value;
}

function ensureOrdersProtection(string $ordersRoot): bool {
    if (!is_dir($ordersRoot) && !mkdir($ordersRoot, 0750, true) && !is_dir($ordersRoot)) {
        return false;
    }

    $htaccessPath = $ordersRoot . DIRECTORY_SEPARATOR . '.htaccess';
    if (!file_exists($htaccessPath)) {
        $rules = "Order allow,deny\nDeny from all\n";
        if (file_put_contents($htaccessPath, $rules) === false) {
            return false;
        }
    }

    $indexPath = $ordersRoot . DIRECTORY_SEPARATOR . 'index.html';
    if (!file_exists($indexPath) && file_put_contents($indexPath, '') === false) {
        return false;
    }

    return true;
}

function saveOrderAsPartitionedJson(array $order, string $orderNo): bool {
    $ordersRoot = __DIR__ . DIRECTORY_SEPARATOR . 'orders';
    if (!ensureOrdersProtection($ordersRoot)) {
        return false;
    }

    $datePath = date('Y' . DIRECTORY_SEPARATOR . 'm' . DIRECTORY_SEPARATOR . 'd');
    $targetDir = $ordersRoot . DIRECTORY_SEPARATOR . $datePath;
    if (!is_dir($targetDir) && !mkdir($targetDir, 0750, true) && !is_dir($targetDir)) {
        return false;
    }

    $fileName = $orderNo . '.json';
    $fullPath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
    $handle = fopen($fullPath, 'x');
    if ($handle === false) {
        return false;
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            return false;
        }

        $json = json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }

        $written = fwrite($handle, $json);
        if ($written === false || $written < strlen($json)) {
            return false;
        }

        fflush($handle);
        return true;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

$currency = "$";
$msg = '';
if (isset($_SESSION['flash_msg'])) {
    $msg = (string) $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}
$total = 0;
$qtydecimaltotal = 0;
$products = [
    'p1' => ['name' => 'Product 1', 'price' => 1.10],
    'p2' => ['name' => 'Product 2', 'price' => 1.20],
    'p3' => ['name' => 'Product 3', 'price' => 1.30],
];

// Handle cart mutation actions via POST + CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action_type'] ?? '') === 'clear_cart') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }
    unset($_SESSION['cart']);
    redirectToHome();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action_type'] ?? '') === 'remove_item') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }
    $removeIndex = (int) ($_POST['remove_index'] ?? -1);
    if (isset($_SESSION['cart'][$removeIndex])) {
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    redirectToHome();
}

// Handle Order Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action_type'] ?? '') === 'submit_order') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $payment = sanitize($_POST['payment'] ?? '');

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$address || !$payment) {
        $msg = "Lütfen tüm gerekli alanları doğru şekilde doldurun.";
    } else {
        $order = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'payment' => $payment,
            'cart' => $_SESSION['cart'] ?? [],
            'date' => date('Y-m-d H:i:s')
        ];

        $saved = false;
        $orderNo = '';
        if (!$demo_mode) {
            for ($attempt = 0; $attempt < 5; $attempt++) {
                $orderNo = generateOrderNumber(8);
                $order['order_no'] = $orderNo;
                $saved = saveOrderAsPartitionedJson($order, $orderNo);
                if ($saved) {
                    break;
                }
            }
        } else {
            $orderNo = generateOrderNumber(8);
            $order['order_no'] = $orderNo;
            $_SESSION['last_demo_order'] = $order; // Demo izleme için
            $saved = true;
        }

        if ($saved) {
            unset($_SESSION['cart']);
            $msg = $demo_mode
                ? "DEMO MODU: Sipariş simüle edildi. Sipariş No: {$orderNo}"
                : "Sipariş başarıyla gönderildi. Sipariş No: {$orderNo}";
        } else {
            $msg = "Sipariş kaydedilemedi. Lütfen tekrar deneyin.";
        }
    }
    setFlashMessage($msg);
    redirectToHome();
}

// Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action_type'] ?? '') === 'add_to_cart') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }

    $productId = (string) ($_POST['product_id'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $selectedProduct = $products[$productId] ?? null;

    if ($selectedProduct && $quantity > 0) {
        $item = $selectedProduct['name'];
        $price = (float) $selectedProduct['price'];
        $exists = false;
        foreach ($_SESSION['cart'] ?? [] as $index => $product) {
            if (($product['product_id'] ?? null) === $productId) {
                $_SESSION['cart'][$index]['quantity'] += $quantity;
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $_SESSION['cart'][] = [
                'product_id' => $productId,
                'item' => $item,
                'unitprice' => $price,
                'quantity' => $quantity
            ];
        }
        $msg = "$item added to cart.";
    } else {
        $msg = "Invalid product or quantity.";
    }
    setFlashMessage($msg);
    redirectToHome();
}

$csrfToken = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Mini Cart</h1>

	<?php if ($demo_mode): ?>
	<div class="alert alert-warning text-center" role="alert">
		<strong>DEMO MODU AKTİF / DEMO MODE ENABLED:</strong><br>
		Bu sitede gönderdiğiniz siparişler <u>gerçek olarak kaydedilmez</u>. Yalnızca test amaçlıdır.<br>
		Orders submitted on this site <u>will not be saved</u>. For demonstration purposes only.
	</div>
	<?php endif; ?>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h4 mb-0">Products</h2>
            <span class="badge text-bg-light">Session cart</span>
        </div>
        <div class="row g-3">
            <?php foreach ($products as $productId => $product): ?>
                <div class="col-md-4">
                    <form method="post" action="index.php" class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5"><?= sanitize($product['name']) ?></h3>
                            <p class="text-muted mb-3"><?= number_format((float) $product['price'], 2) ?> <?= $currency ?></p>
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <input type="hidden" name="action_type" value="add_to_cart">
                            <label for="qty-<?= $productId ?>" class="form-label small mb-1">Quantity</label>
                            <input id="qty-<?= $productId ?>" type="number" name="quantity" value="1" min="1" class="form-control mb-3">
                            <button class="btn btn-primary mt-auto">Add to Cart</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="mb-4">
        <h2 class="h4 mb-3">My Cart</h2>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                        <tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_SESSION['cart'] as $i => $product):
                            $lineTotal = $product['unitprice'] * $product['quantity'];
                            $total += $lineTotal;
                            $qtydecimaltotal += $product['quantity'];
                        ?>
                            <tr>
                                <td><?= sanitize($product['item']) ?></td>
                                <td><?= number_format($product['unitprice'], 2) ?> <?= $currency ?></td>
                                <td><?= $product['quantity'] ?></td>
                                <td><?= number_format($lineTotal, 2) ?> <?= $currency ?></td>
                                <td>
                                    <form method="post" action="index.php" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="action_type" value="remove_item">
                                        <input type="hidden" name="remove_index" value="<?= $i ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <td><?= $qtydecimaltotal ?></td>
                            <td colspan="2"><?= number_format($total, 2) ?> <?= $currency ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <form method="post" action="index.php" class="d-inline">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="action_type" value="clear_cart">
                    <button type="submit" class="btn btn-warning">Clear Cart</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary mb-0">Your cart is empty.</div>
        <?php endif; ?>
    </section>

    <?php if (!empty($_SESSION['cart'])): ?>
        <section class="mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h4 mb-0">Order Form</h2>
                        <span class="small text-muted">Total: <?= number_format($total, 2) ?> <?= $currency ?></span>
                    </div>
                    <form method="post" action="index.php" class="row g-3">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="action_type" value="submit_order">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" name="name" class="form-control" maxlength="80" autocomplete="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" maxlength="120" autocomplete="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" type="tel" name="phone" class="form-control" inputmode="tel" autocomplete="tel" pattern="[0-9+()\s-]{10,20}" placeholder="+90 555 123 45 67">
                            <div class="form-text">Use 10-20 chars. Example: +90 555 123 45 67</div>
                        </div>
                        <div class="col-md-6">
                            <label for="payment" class="form-label">Payment Method</label>
                            <select id="payment" name="payment" class="form-select" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" rows="3" class="form-control" maxlength="500" autocomplete="street-address" required></textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Place Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    <?php endif; ?>
	
    <div class="text-center mt-4">
        <a href="https://github.com/anbarli/PHP-SBCS" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-primary">
            GitHub Projesini Görüntüle / View Project on GitHub
        </a>
    </div>
</div>
</body>
</html>
