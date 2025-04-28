<?php
// edit_item.php
include('db.php');

// Get the item ID from the URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the existing item data from the database
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error="User not found!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and update the item data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $user_type = trim($_POST['user_type']);

    // Update the item in the database
    $query = "UPDATE users SET name = :name, email = :email, user_type = :user_type WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':user_type' => $user_type,
        ':id' => $user_id
    ]);

    $success = "User updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/custom/styles.css" rel="stylesheet">
</head>
<body>
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="content-wrapper">
    <div class="container-fluid">
        <h1>Edit User</h1>
        <?php if (isset($success)) {
            echo "<div class='alert alert-success'>$success</div>";
        }
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" value="<?=$user['name'];?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email"  value="<?=$user['email'];?>" required>
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label">User type</label>
                <select name="user_type" class="form-control" id="user_type" required>
                    <option value="Seller" <?= $user['user_type']=='Seller'?'selected':'';?>>Seller</option>
                    <option value="Admin" <?= $user['user_type']=='Admin'?'selected':'';?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
</body>
</html>
