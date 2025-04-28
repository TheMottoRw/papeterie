<?php
// expenses.php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $user_type = trim($_POST['user_type']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Prepare the query to insert user data
        //check if user exists
        $qy = "SELECT * FROM users WHERE email=:email";
        $st_exist = $pdo->prepare($qy);
        if ($st_exist->execute([":email" => "$email"])) {
            if ($st_exist->rowCount() > 0) {
                $error = "User already exists";
            } else {
                // Execute the query
                $query = "INSERT INTO users (name, email,user_type, password) VALUES (:name, :email,:user_type, :password)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':name' => $name, ':email' => $email, ':user_type' => $user_type, ':password' => $hashed_password]);
                $success = "Registration successful!";
            }
        }

    }
}

if (isset($_GET['delete'])) {
    $query = "DELETE FROM users  WHERE id=:id";
    $stmt = $pdo->prepare($query);

    if($stmt->execute([
        ':id' => $_GET['id']
    ])){
        $success = "User deleted successfully!";
    }else{
        $error="Something went wrong";
    }

}

$query = "SELECT * FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/custom/styles.css" rel="stylesheet">
</head>
<body>
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="content-wrapper">
    <div class="container-fluid">
        <h1>Add New User</h1>
        <?php if (isset($success)) {
            echo "<div class='alert alert-success'>$success</div>";
        }
        if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label">User type</label>
                <select name="user_type" class="form-control" id="user_type" required>
                    <option value="Seller">Seller</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign up</button>
        </form>
        <h1>Users</h1>
        <!-- Search Box -->
        <div class="mb-3">
            <input type="text" id="searchBox" class="form-control" placeholder="Search...">
        </div>
        <table class="table" id="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User type</th>
                <th>Registered on</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td><a href="edit_user.php?id=<?= htmlspecialchars($user['id']); ?>"
                           class="btn btn-outline-warning">Edit</a><a
                                href="users.php?delete=yes&id=<?= htmlspecialchars($user['id']); ?>"
                                class="btn btn-outline-danger">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
