<?php
include 'db.php';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date']." 00:00:01";
    $end_date = $_POST['end_date']." 23:59:59";

    // Step 1: Retrieve Sales Data
    $salesQuery = "SELECT 
                    s.created_at, 
                    i.name AS item_name, 
                    s.quantity, 
                    s.selling_price, 
                    s.total_price, 
                    s.profit 
                  FROM sales s 
                  JOIN items i ON s.item_id = i.id
                  WHERE s.created_at BETWEEN :start_date AND :end_date";
    $salesStmt = $pdo->prepare($salesQuery);
    $salesStmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
    $salesData = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total income and total profit
    $total_income = 0;
    $total_profit = 0;
    foreach ($salesData as $sale) {
        $total_income += $sale['total_price'];
        $total_profit += $sale['profit'];
    }

    // Step 2: Retrieve Expenses Data
    $expensesQuery = "SELECT 
                        e.created_at, 
                        e.expense_name, 
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

    // Step 3: Calculate the overall profit (Income - Expenses)
    $net_profit = $total_income - $total_expenses;

    // Output the data or use it in the report section
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales and Expense Report</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="assets/xlsx/xlsx.full.min.js"></script>

</head>
<body>
<?php include('sidebar.php'); ?>
<div class="container">
    <h1>Sales and Expense Report</h1>

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
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>

    <hr>
    <button id="exportButton" class="btn btn-success">Export to Excel</button>



        <hr>

        <h3>Expenses Report (From <?php echo htmlspecialchars($start_date); ?>
            to <?php echo htmlspecialchars($end_date); ?>)</h3>

        <table class="table table-bordered" id="expensesTable">
            <thead>
            <tr>
                <th>Date</th>
                <th>Expense Name</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($expensesData as $expense): ?>
                <tr>
                    <td><?php echo htmlspecialchars($expense['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($expense['expense_name']); ?></td>
                    <td><?php echo htmlspecialchars($expense['expense_price']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total Expenses: <?php echo number_format($total_expenses, 2); ?></h4>

        <hr>

        <h3>Net Profit (Income - Expenses): <?php echo number_format($net_profit, 2); ?></h3>
</div>
</body>
<script>
    document.getElementById('exportButton').addEventListener('click', function () {
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

        // Get the data from the Expenses table
        var expensesTable = document.getElementById('expensesTable');
        var expensesData = [];
        for (var i = 0, row; row = expensesTable.rows[i]; i++) {
            var rowData = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                rowData.push(col.innerText);
            }
            expensesData.push(rowData);
        }
        // Get the data from the Expenses table
        var profitTable = document.getElementById('profitTable');
        var profitData = [];
        for (var i = 0, row; row = profitTable.rows[i]; i++) {
            var rowData = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                rowData.push(col.innerText);
            }
            profitData.push(rowData);
        }

        // Create a new workbook
        var wb = XLSX.utils.book_new();

        // Add Sales data sheet
        var salesWorksheet = XLSX.utils.aoa_to_sheet(salesData);
        XLSX.utils.book_append_sheet(wb, salesWorksheet, 'Sales Report');

        // Add Expenses data sheet
        var expensesWorksheet = XLSX.utils.aoa_to_sheet(expensesData);
        XLSX.utils.book_append_sheet(wb, expensesWorksheet, 'Expenses Report');
        // Add Expenses data sheet
        var profitWorksheet = XLSX.utils.aoa_to_sheet(profitData);
        XLSX.utils.book_append_sheet(wb, profitWorksheet, 'Profit Report');

        // Export the workbook to Excel
        XLSX.writeFile(wb, 'report.xlsx');
    });
</script>
</html>
