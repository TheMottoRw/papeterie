<?php
// add_service.php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
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
</body>
</html>
