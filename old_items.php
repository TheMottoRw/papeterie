<?php
// old_items.php
include('db.php');

$query = "SELECT * FROM items";
$stmt = $pdo->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="container mt-5">
    <h1>Items <a href="items.php" class="btn btn-primary pull-right" style="margin-left: 70%">Add item</a></h1>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Buying Price</th>
            <th>Selling Price</th>
            <th>Quantity</th>
            <th>Registered on</th>
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
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
