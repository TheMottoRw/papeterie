<?php
// edit_service.php
include('db.php');

// Get the expense ID from the URL
$expense_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the existing service data from the database
$query = "SELECT * FROM expenses WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $expense_id]);
$expense = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$expense) {
    die("Expense not found!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and update the service data
    $expense_name = trim($_POST['expense_name']);
    $price = trim($_POST['price']);
    $reason = trim($_POST['reason']);
    $comment = trim($_POST['comment']);

    // Update the service in the database
    $query = "UPDATE expenses SET expense_name = :expense_name, price = :price, reason = :reason, comment = :comment WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':expense_name' => $expense_name,
        ':reason' => $reason,
        ':comment' => $comment,
        ':price' => $price,
        ':id' => $expense_id
    ]);

    $success = "Expense updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit expense</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>

<div class="container mt-5">
    <h1>Edit Expense</h1>
    <?php if (isset($success)) {
        echo "<div class='alert alert-success'>$success</div>";
    } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="expense_name" class="form-label">Expense Name</label>
            <input type="text" name="expense_name" class="form-control" id="expense_name"
                   value="<?= htmlspecialchars($expense['expense_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <input type="text" name="reason" class="form-control" id="reason"
                   value="<?= htmlspecialchars($expense['reason']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price"
                   value="<?= htmlspecialchars($expense['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea cols="3" name="comment" class="form-control" id="comment" required><?= htmlspecialchars($expense['comment']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Expense</button>
    </form>
</div>
</body>
</html>
