<?php
include 'db.php';
$start_date='';
$end_date='';
$total_services = 0;
$servicesData = [];
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'] . " 00:00:01";
    $end_date = $_POST['end_date'] . " 23:59:59";
// Step 2: Retrieve Services Data
    $servicesQuery = "SELECT 
                        e.created_at, 
                        e.service_name,
                        e.price AS service_price 
                      FROM services e
                      WHERE e.created_at BETWEEN :start_date AND :end_date";
    $servicesStmt = $pdo->prepare($servicesQuery);
    $servicesStmt->execute([':start_date' => $start_date, ':end_date' => $end_date]);
    $servicesData = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total services
    $total_services = 0;
    foreach ($servicesData as $service) {
        $total_services += $service['service_price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Report</title>
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
            <h2>Services report</h2>
            <div class="card">
                <div class="card-body">
                    <!-- Report content will go here -->
                    <!-- Report Table -->
                    <h6 id="report-title">Services Report From <?php echo htmlspecialchars(substr($start_date,0,10)); ?>
                        to <?php echo htmlspecialchars(substr($end_date,0,10)); ?></h6>
                    <table class="table table-bordered" id="servicesTable" >
                        <thead>
                        <tr>
                            <th>Service name</th>
                            <th>Price</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($servicesData as $service){ ?>
                            <tr>
                                <td><?php echo $service['service_name']; ?></td>
                                <td><?php echo number_format($service['service_price'], 2); ?> RWF</td>
                                <td><?php echo substr($service['created_at'],0,16); ?></td>
                            </tr>
                        <?php }; ?>
                        <tr><td colspan="2">Total service</td>
                            <td colspan="3"><?php echo number_format($total_services, 2); ?> RWF</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-3">Total service: <?php echo number_format($total_services, 2); ?> RWF</div>
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
        var salesTable = document.getElementById('servicesTable');
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
        XLSX.utils.book_append_sheet(wb, salesWorksheet, "Service report");
        XLSX.writeFile(wb, report_title+'.xlsx');

    })
</script>
