<?php
// edit_service.php
include('db.php');

// Get the service ID from the URL
$service_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the existing service data from the database
$query = "SELECT * FROM services WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Service not found!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and update the service data
    $service_name = trim($_POST['service_name']);
    $price = trim($_POST['price']);

    // Update the service in the database
    $query = "UPDATE services SET service_name = :service_name, price = :price WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':service_name' => $service_name,
        ':price' => $price,
        ':id' => $service_id
    ]);

    $success = "Service updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="container mt-5">
    <h1>Edit Service</h1>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="service_name" class="form-label">Service Name</label>
            <input type="text" name="service_name" class="form-control" id="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price" value="<?php echo htmlspecialchars($service['price']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Service</button>
    </form>
</div>
</body>
</html>
