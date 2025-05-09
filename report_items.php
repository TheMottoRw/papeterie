<?php
include 'db.php';

$start_date='';
$end_date='';
$items = [];
require_once "php/invoices.php";
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'] . " 00:00:01";
    $end_date = $_POST['end_date'] . " 23:59:59";

    $query = "SELECT * FROM items WHERE created_at BETWEEN '$start_date' AND '$end_date' ORDER BY created_at ASC";
    $stmt = $pdo->query($query);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>
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
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required  value="<?=$start_date;?>">
            </div>
            <div class="col">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required value="<?=$end_date;?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
        <button type="button" id="exportButton" class="btn btn-outline-success">Export to Excel</button>

    </form>

    <hr>
    <div class="row">
        <div class="col-12">
            <h4>Stock Report</h4>
            <div class="card">
                <div class="card-body">
                    <!-- Report content will go here -->
                    <!-- Report Table -->
                    <h6 id="report-title">Stock Report From <?php echo htmlspecialchars(substr($start_date,0,10)); ?>
                        to <?php echo htmlspecialchars(substr($end_date,0,10)); ?></h6>

                    <table class="table" id="table">
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
                        <?php
                        $stockValue = 0;
                        $totalQuantity = 0;
                        foreach ($items as $k=>$item){
                            $stockValue+= $item['quantity']*$item['selling_price'];
                            $totalQuantity+=$item['quantity'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($k+1); ?></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['buying_price']); ?> RWF</td>
                                <td><?php echo htmlspecialchars($item['selling_price']); ?> RWF</td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                            </tr>
                        <?php }; ?>
                        <tr>
                            <th colspan="3">Stock value</th>
                            <td><?php echo $stockValue; ?> RWF</td>
                            <th>Total quantity</th>
                            <td colspan="2"><?php echo $totalQuantity; ?></td>
                        </tr>
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
        var salesTable = document.getElementById('salesTable');
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
        XLSX.utils.book_append_sheet(wb, salesWorksheet, "Sales report");
        XLSX.writeFile(wb, report_title+'.xlsx');

    })
</script>
