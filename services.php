<?php
// services.php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $service_name = trim($_POST['service_name']);
    $price = trim($_POST['price']);

    // Insert the data into the services table
    $query = "INSERT INTO services (service_name, price) VALUES (:service_name, :price)";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':service_name' => $service_name,
        ':price' => $price
    ]);

    $success = "Service added successfully!";
}
if(isset($_GET['delete'])){
    $query = "DELETE FROM services  WHERE id=:id";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':id' => $_GET['id']
    ]);

    $success = "Service deleted successfully!";
}

$query = "SELECT * FROM services";
$stmt = $pdo->query($query);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flexd">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="container mt-5">
    <h1>Add New Service</h1>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="service_name" class="form-label">Service Name</label>
            <input type="text" name="service_name" class="form-control" id="service_name" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Service</button>
    </form>
</div>

<div class="container mt-5">
    <h1>Services</h1>
    <!-- Search Box -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search...">
    </div>
    <table class="table" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Price</th>
            <th>Registered on</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['id']); ?></td>
                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                <td><?php echo htmlspecialchars($service['price']); ?></td>
                <td><?php echo htmlspecialchars($service['created_at']); ?></td>
                <td><a href="edit_service.php?id=<?=htmlspecialchars($service['id']); ?>" class="btn btn-outline-warning">Edit</a><a href="services.php?delete=yes&id=<?=htmlspecialchars($service['id']); ?>" class="btn btn-outline-danger">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<script src="script.js"></script>

