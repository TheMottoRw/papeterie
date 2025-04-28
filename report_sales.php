<?php
include 'db.php';
require_once "php/invoices.php";
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'] . " 00:00:01";
    $end_date = $_POST['end_date'] . " 23:59:59";

// Step 1: Retrieve Sales Data
    $invoice = new Invoices();
    $salesData = json_decode($invoice->getInvoices(['request'=>'report']), true);


// Calculate total income and total profit
    $total_income = 0;
    $total_profit = 0;
    $total_paid = 0;
    $total_debt = 0;
    foreach ($salesData as $sale) {
        $total_income += $sale['total_amount'];
        $total_profit += $sale['total_profit'];
        $total_paid += $sale['paid'];
        $total_debt += $sale['remain'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
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
            <h4>Sales Report</h4>
            <div class="card">
                <div class="card-body">
                    <!-- Report content will go here -->
                    <!-- Report Table -->
                    <h6 id="report-title">Sales Report From <?php echo htmlspecialchars(substr($start_date,0,10)); ?>
                        to <?php echo htmlspecialchars(substr($end_date,0,10)); ?></h6>

                    <table class="table table-bordered" id="salesTable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client name</th>
                            <th>Client phone</th>
                            <th>Invoice Number</th>
                            <th>Total amount</th>
                            <th>Profit</th>
                            <th>Paid</th>
                            <th>Remain</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($salesData as $sale){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($sale['regdate'],0,16)); ?></td>
                                <td><?php echo htmlspecialchars($sale['client_name']); ?></td>
                                <td><?php echo htmlspecialchars($sale['client_phone']); ?></td>
                                <td><?php echo htmlspecialchars($sale['invoice_identifier']); ?></td>
                                <td><?php echo htmlspecialchars($sale['total_amount']); ?> RWF</td>
                                <td><?php echo htmlspecialchars($sale['profit']); ?> RWF</td>
                                <td><?php echo htmlspecialchars($sale['paid']); ?> RWF</td>
                                <td><?php echo htmlspecialchars($sale['remain']); ?> RWF</td>
                            </tr>
                        <?php }; ?>
                        <tr style="display: none">
                            <td>Total sales</td>
                            <td><?php echo number_format($total_income, 2); ?> RWF</td>
                            <td>Total profit</td>
                            <td><?php echo number_format($total_profit, 2); ?> RWF</td>
                            <td>Payment Received </td>
                            <td><?php echo number_format($total_paid, 2); ?> RWF</td>
                            <td>Total in Debt</td>
                            <td><?php echo number_format($total_debt, 2); ?> RWF</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-3">Total Profit: <?php echo number_format($total_profit, 2); ?> RWF</div>
                        <div class="col-3">Total Sales Income: <?php echo number_format($total_income, 2); ?> RWF</div>
                        <div class="col-3">Payment Received: <?php echo number_format($total_paid, 2); ?> RWF</div>
                        <div class="col-3">Total in Debt: <?php echo number_format($total_debt, 2); ?> RWF</div>
                    <br>

                    </div>
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
        XLSX.utils.book_append_sheet(wb, salesWorksheet, "Sales report");
        XLSX.writeFile(wb, report_title+'.xlsx');

    })
</script>
