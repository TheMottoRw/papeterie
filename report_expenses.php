<?php
include 'db.php';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'] . " 00:00:01";
    $end_date = $_POST['end_date'] . " 23:59:59";
// Step 2: Retrieve Expenses Data
    $expensesQuery = "SELECT 
                        e.created_at, 
                        e.expense_name, 
                        e.reason, 
                        e.comment, 
                        e.price AS expense_price 
                      FROM expenses e
                      WHERE e.created_at BETWEEN :start_date AND :end_date";
    $expensesStmt = $pdo->prepare($expensesQuery);
    $expensesStmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
    $expensesData = $expensesStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total expenses
    $total_expenses = 0;
    foreach ($expensesData as $expense) {
        $total_expenses += $expense['expense_price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Report</title>
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
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
        <button type="button" id="exportButton" class="btn btn-outline-success btn-sm">Export to Excel</button>
    </form>
    <hr>
    <div class="row">
        <div class="col-12">
            <h2>Expenses report</h2>
            <div class="card">
                <div class="card-body">
                    <!-- Report content will go here -->
                    <!-- Report Table -->
                    <h6 id="report-title">Expenses Report From <?php echo htmlspecialchars(substr($start_date,0,10)); ?>
                        to <?php echo htmlspecialchars(substr($end_date,0,10)); ?></h6>
                    <table class="table table-bordered" id="expensesTable" >
                        <thead>
                        <tr>
                            <th>Expense name</th>
                            <th>Reason</th>
                            <th>Price</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($expensesData as $expense){ ?>
                            <tr>
                                <td><?php echo $expense['expense_name']; ?></td>
                                <td><?php echo $expense['reason']; ?></td>
                                <td><?php echo number_format($expense['expense_price'], 2); ?> RWF</td>
                                <td><?php echo $expense['comment']; ?></td>
                                <td><?php echo substr($expense['created_at'],0,16); ?></td>
                            </tr>
                        <?php }; ?>
                        <tr><td colspan="2">Total expense</td>
                        <td colspan="3"><?php echo number_format($total_expenses, 2); ?> RWF</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-3">Total expense: <?php echo number_format($total_expenses, 2); ?> RWF</div>
                        <br>
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
        var salesTable = document.getElementById('expensesTable');
        var salesData = [];
        for (var i = 0, row; row = salesTable.rows[i]; i++) {
            var rowData = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                rowData.push(col.innerText);
            }
            if(i==salesTable.getElementsByTagName("tr").length-1) {
                salesData.push(['','','','','','','',''])
                salesData.push(['','','','','','','',''])
            }
            salesData.push(rowData);
        }
        var report_title = document.querySelector("#report-title").innerHTML;
        // Create a new workbook
        var wb = XLSX.utils.book_new();

        // Add Sales data sheet
        var salesWorksheet = XLSX.utils.aoa_to_sheet(salesData);
        XLSX.utils.book_append_sheet(wb, salesWorksheet, "Expense report");
        XLSX.writeFile(wb, report_title+'.xlsx');

    })
</script>
