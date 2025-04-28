<?php
// expenses.php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $expense_name = trim($_POST['expense_name']);
    $price = trim($_POST['price']);
    $comment = trim($_POST['comment']);
    $reason = trim($_POST['reason']);

    // Insert the data into the services table
    $query = "INSERT INTO expenses (expense_name,reason, price,comment) VALUES (:expname,:reason, :price,:comment)";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':expname' => $expense_name,
        ':reason' => $reason,
        ':price' => $price,
        ':comment' => $comment
    ]);

    $success = "Expense added successfully!";
}
if(isset($_GET['delete'])){
    $query = "DELETE FROM expenses  WHERE id=:id";
    $stmt = $pdo->prepare($query);

    $stmt->execute([
        ':id' => $_GET['id']
    ]);

    $success = "Expense deleted successfully!";
}

$query = "SELECT * FROM expenses";
$stmt = $pdo->query($query);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flexd">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>
<div class="container mt-5">
    <h1>Add New expense</h1>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="expense_name" class="form-label">Expense Name</label>
            <input type="text" name="expense_name" class="form-control" id="expense_name" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" id="reason" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price" required>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea cols="3" name="comment" class="form-control" id="comment" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Expense</button>
    </form>
</div>
<div class="container mt-5">
    <h1>Expenses</h1>    <!-- Search Box -->
    <!-- Search Box -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search...">
    </div>
    <table class="table" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Expense Name</th>
            <th>Reason</th>
            <th>Price</th>
            <th>Comment</th>
            <th>Registered on</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($expenses as $expense): ?>
            <tr>
                <td><?php echo htmlspecialchars($expense['id']); ?></td>
                <td><?php echo htmlspecialchars($expense['expense_name']); ?></td>
                <td><?php echo htmlspecialchars($expense['reason']); ?></td>
                <td><?php echo htmlspecialchars($expense['price']); ?></td>
                <td><?php echo htmlspecialchars($expense['comment']); ?></td>
                <td><?php echo htmlspecialchars($expense['created_at']); ?></td>
                <td><a href="edit_expenses.php?id=<?=htmlspecialchars($expense['id']); ?>" class="btn btn-outline-warning">Edit</a><a href="expenses.php?delete=yes&id=<?=htmlspecialchars($expense['id']); ?>" class="btn btn-outline-danger">Delete</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<script src="script.js"></script>
