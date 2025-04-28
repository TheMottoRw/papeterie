<?php
// edit_item.php
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/custom/styles.css" rel="stylesheet">
</head>
<body>
<!-- Include Sidebar -->
<?php include('sidebar.php');
?>
<?php

// Get the item ID from the URL
$item_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// Fetch the existing item data from the database
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $item_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error = "User not found!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and update the item data
    $current_password = $_POST['current_password'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $hashed_new_password = password_hash($password, PASSWORD_DEFAULT);
    if($current_password==$password) $error="Password are the same";
    else if($confirm_password!=$password) $error="New password and confirm password does not match";
    else {
        if (password_verify($current_password, $user['password'])) {

            // Update the item in the database
            $query = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':password' => $hashed_new_password,
                ':id' => $item_id
            ]);

            $success = "Password updated successfully!";
        }else $error = "Invalid current password";

    }
}

?>
<div class="content-wrapper">
    <div class="container-fluid">
        <h1>Change password</h1>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; }
        if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Current password</label>
                <input type="password" name="current_password" class="form-control" id="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" id="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change password</button>
        </form>
    </div>
</div>
</body>
</html>
