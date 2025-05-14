<?php
include 'db.php';
$quantity = 0;
$items = [];
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];

    $query = "SELECT * FROM items WHERE quantity<='$quantity' ORDER BY quantity ASC";
    $stmt = $pdo->query($query);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out of stock Report</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="assets/xlsx/xlsx.full.min.js"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container">
    <!-- Date Filter Form -->
    <form method="POST">
        <div class="row mb-3">
            <div class="col">
                <label for="start_date" class="form-label">Minimum quantity</label>
                <input type="number" name="quantity" class="form-control" required value="<?=$quantity;?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
        <button type="button" id="exportButton" class="btn btn-outline-success">Export to Excel</button>

    </form>

    <hr>
    <div class="row">
        <div class="col-12">
            <h4>Out of stock report</h4>
            <div class="card">
                <div class="card-body">
                    <!-- Report content will go here -->
                    <!-- Report Table -->
                    <h6 id="report-title">Out of stock Report Min. quantity <?php echo htmlspecialchars(substr($quantity,0,10)); ?>
                        </h6>

                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Buying Price</th>
                            <th>Selling Price</th>
                            <th>Available quantity</th>
                            <th>Registered on</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $k=>$item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($k+1); ?></td>
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
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    document.getElementById('exportButton').addEventListener('click', function () {
        // event.preventDefault();
        console.log('Exporting to Excel');
        // Get the data from the Sales table
        var salesTable = document.getElementById('table');
        var salesData = [];
        for (var i = 0, row; row = salesTable.rows[i]; i++) {
            var rowData = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                rowData.push(col.innerText);
            }
            salesData.push(rowData);
        }
        var report_title = document.querySelector("#report-title").innerHTML;
        // Create a new workbook
        var wb = XLSX.utils.book_new();

        // Add Sales data sheet
        var salesWorksheet = XLSX.utils.aoa_to_sheet(salesData);
        XLSX.utils.book_append_sheet(wb, salesWorksheet, "Out of stock report");
        XLSX.writeFile(wb, report_title+'.xlsx');

    })
</script>
