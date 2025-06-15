<?php
// Enable strict typing / Kesin tür kontrolü etkinleştir
declare(strict_types=1);
error_reporting(E_ALL);
session_start();

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

$currency = "$";
$msg = "";
$total = 0;
$qtydecimaltotal = 0;

// Handle GET operations first
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header('Location: index.php');
    exit;
}

if (isset($_GET['remove'])) {
    $removeIndex = (int) $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: index.php');
    exit;
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
        $msg = "Please fill in all required fields correctly.";
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

        $orders = [];
        $file = 'orders.json';
        if (file_exists($file)) {
            $orders = json_decode(file_get_contents($file), true) ?: [];
        }
        $orders[] = $order;
        file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT));

        unset($_SESSION['cart']);
        $msg = "Order successfully submitted!";
    }
}

// Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action_type'] ?? '') === 'add_to_cart') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }

    $item = sanitize($_POST['item'] ?? '');
    $price = (float) str_replace(',', '.', $_POST['price'] ?? '0');
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if ($item && $price > 0 && $quantity > 0) {
        $exists = false;
        foreach ($_SESSION['cart'] ?? [] as $index => $product) {
            if ($product['item'] === $item) {
                $_SESSION['cart'][$index]['quantity'] += $quantity;
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $_SESSION['cart'][] = [
                'item' => $item,
                'unitprice' => $price,
                'quantity' => $quantity
            ];
        }
        $msg = "$item added to cart.";
    }
}

$csrfToken = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PHP Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">Mini Cart</h1>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <div class="row mb-3">
        <?php foreach ([['Product 1', 1.10], ['Product 2', 1.20], ['Product 3', 1.30]] as [$name, $price]): ?>
            <div class="col-md-4">
                <form method="post" action="index.php" class="card p-3">
                    <h5><?= $name ?></h5>
                    <p><?= number_format($price, 2) ?> <?= $currency ?></p>
                    <input type="hidden" name="item" value="<?= $name ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="action_type" value="add_to_cart">
                    <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                    <button class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>My Cart</h3>
    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table table-bordered">
            <thead>
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
                    <td><a href="?remove=<?= $i ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr><th colspan="2">Total</th><td><?= $qtydecimaltotal ?></td><td colspan="2"><?= number_format($total, 2) ?> <?= $currency ?></td></tr>
            </tfoot>
        </table>
        <a href="?clear=1" class="btn btn-warning">Clear Cart</a>

        <h3 class="mt-4">Order Form</h3>
        <form method="post" action="index.php" class="mt-3">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="action_type" value="submit_order">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" pattern="\\d*">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="payment" class="form-label">Payment Method</label>
                <select name="payment" class="form-select" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
