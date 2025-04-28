<?php
// items.php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $buying_price = trim($_POST['buying_price']);
    $selling_price = trim($_POST['selling_price']);
    $quantity = trim($_POST['quantity']);

    // Insert the data into the items table
    $query = "INSERT INTO items (name, buying_price, selling_price, quantity) 
              VALUES (:name, :buying_price, :selling_price, :quantity)";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':name' => $name,
        ':buying_price' => $buying_price,
        ':selling_price' => $selling_price,
        ':quantity' => $quantity
    ]);

    $success = "Item added successfully!";
}

if(isset($_GET['delete'])){
    $query = "DELETE FROM items  WHERE id=:id";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':id' => $_GET['id']
    ]);

    $success = "Item deleted successfully!";
}
$query = "SELECT * FROM items";
$stmt = $pdo->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flexd">
<?php include('sidebar.php'); ?>

<div class="container">
    <h1>Add New Item</h1>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Item Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="buying_price" class="form-label">Buying Price</label>
            <input type="number" step="0.01" name="buying_price" class="form-control" id="buying_price" required>
        </div>
        <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" step="0.01" name="selling_price" class="form-control" id="selling_price" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" id="quantity" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</div>
<div class="container">
    <h1>Items </h1>

    <!-- Search Box -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search...">
    </div>
    <table class="table" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
            <th>Quantity</th>
            <th>Registered on</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['buying_price']); ?></td>
                <td><?php echo htmlspecialchars($item['selling_price']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                <td><a href="edit_item.php?id=<?=htmlspecialchars($item['id']); ?>" class="btn btn-outline-warning">Edit</a><a href="items.php?delete=yes&id=<?=htmlspecialchars($item['id']); ?>" class="btn btn-outline-danger">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<script src="script.js"></script>
