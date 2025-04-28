<?php
// edit_item.php
include('db.php');

// Get the item ID from the URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the existing item data from the database
$query = "SELECT * FROM items WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and update the item data
    $name = trim($_POST['name']);
    $buying_price = trim($_POST['buying_price']);
    $selling_price = trim($_POST['selling_price']);
    $quantity = trim($_POST['quantity']);

    // Update the item in the database
    $query = "UPDATE items SET name = :name, buying_price = :buying_price, selling_price = :selling_price, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':name' => $name,
        ':buying_price' => $buying_price,
        ':selling_price' => $selling_price,
        ':quantity' => $quantity,
        ':id' => $item_id
    ]);

    $success = "Item updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="container mt-5">
    <h1>Edit Item</h1>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Item Name</label>
            <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="buying_price" class="form-label">Buying Price</label>
            <input type="number" step="0.01" name="buying_price" class="form-control" id="buying_price" value="<?php echo htmlspecialchars($item['buying_price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" step="0.01" name="selling_price" class="form-control" id="selling_price" value="<?php echo htmlspecialchars($item['selling_price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" id="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Item</button>
    </form>
</div>
</body>
</html>
