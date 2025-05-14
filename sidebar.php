<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include_once "permission_manager.php";
//if(!is_page_access_allowed()){
//    echo "<script>alert('Unauthorized access');window.location='logout.php';</script>";
//}
?>
<!-- sidebar.php -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<input type="hidden" id="user-id" value="<?= $_SESSION['user_id']; ?>">
<input type="hidden" id="user-type" value="<?= $_SESSION['user_type']; ?>">
<div class="sidebar bg-dark text-white" style="height: 100vh; width: 250px; position: fixed; top: 0; left: 0; padding-top: 20px;">
    <h2 class="text-center text-white">Stationery</h2>
    <ul class="list-unstyled" style="padding-left: 40px">

        <li class="nav-item" id="menu-user" style="display:none"><a href="users.php" class="nav-link text-white d-block p-2">Users</a></li>
        <li class="nav-item" id="menu-item" style="display:block"><a href="items.php" class="nav-link text-white d-block p-2">Items</a></li>
        <li class="nav-item" id="menu-service" style="display:block"><a href="services.php" class="nav-link text-white d-block p-2">Services</a></li>
        <li class="nav-item" id="menu-expense" style="display:block"><a href="expenses.php" class="nav-link text-white d-block p-2">Expenses</a></li>
        <li class="nav-item" id="menu-sale" style="display:block"><a href="invoices.php"  class="nav-link text-white d-block p-2">Sales</a></li>
        <li class="nav-item dropdown" id="menu-report" style="display:none">
            <a class="nav-link dropdown-toggle text-white d-block p-2" href="#" id="reportDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
                Report
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="reportDropdown">
                <li><a class="dropdown-item" href="report_sales.php">Sales</a></li>
                <li><a class="dropdown-item" href="report_debt.php">Debt</a></li>
                <li><a class="dropdown-item" href="report_items.php">Stock</a></li>
                <li><a class="dropdown-item" href="report_outofstock_items.php">Out of Stock</a></li>
                <li><a class="dropdown-item" href="report_expenses.php">Expenses</a></li>
                <li><a class="dropdown-item" href="report_services.php">Service</a></li>
            </ul>
        </li>
        <li class="nav-item"><a href="change_password.php" class="nav-link text-white d-block p-2">Change password</a></li>


        <li><a href="logout.php" class="text-white d-block p-2">Logout (<?= $_SESSION['user_name']; ?>) </a></li>
    </ul>
</div>
<script src="script.js"></script>