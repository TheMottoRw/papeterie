<?php
$seller_pages=array("items.php","edit_item.php","services.php","edit_services.php","expenses.php","edit_expenses.php","sales.php","change_password.php");
$admin_pages = array("report.php","users.php","edit_users.php");
$roleAccess = [
    'Seller' => ['items.php', 'services.php', 'expenses.php', 'sales.php','change_password.php', 'logout.php'],
    'Admin' => ['items.php', 'services.php', 'expenses.php', 'invoices.php', 'report_sales.php','report_debt.php','report_items.php','report_items.php','report_report_outofstock_items.php','report_services.php','users.php','edit_user.php', 'change_password.php', 'logout.php']
];// Function to get the current page from URL
function role_access()
{
    global $roleAccess;
    return $roleAccess[$_SESSION['user_type']];
}
function menu_items(){

    // Define menu items
    $menuItems = [
        ['url' => 'items.php', 'text' => 'Items'],
        ['url' => 'services.php', 'text' => 'Services'],
        ['url' => 'expenses.php', 'text' => 'Expenses'],
        ['url' => 'sales.php', 'text' => 'Sales'],
        ['url' => 'report.php', 'text' => 'Report'],
        ['url' => 'users.php', 'text' => 'Users'],
        ['url' => 'change_password.php', 'text' => 'Change password'],
        ['url' => 'logout.php', 'text' => "Logout (" . ($_SESSION['user_name'] ?? '') . ")"]
    ];
return $menuItems;
}
function getCurrentPage() {
    // Get the full URL
    $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    // Parse the URL to get its components
    $urlParts = parse_url($fullUrl);

    // Get the path component
    $path = $urlParts['path'];

    // Extract just the filename
    $page = basename($path);

    return $page;
}

// Alternative method to get just the script filename
function getCurrentPageSimple() {
    return basename($_SERVER['SCRIPT_NAME']);
}

// Example using REQUEST_URI for applications with routing
function getCurrentPageWithRequestUri() {
    // Get the request URI
    $requestUri = $_SERVER['REQUEST_URI'];

    // Remove query string if present
    $requestUri = strtok($requestUri, '?');

    // Extract base path from the request
    $basePath = basename($requestUri);

    return $basePath;
}
function is_page_access_allowed($page=null){
    global $admin_pages;
    global $seller_pages;
    $isAllowed = false;
    // Get the current page
    $currentPage = is_null($page)?getCurrentPage():"";
// Or use the simpler method
// $currentPage = getCurrentPageSimple();

// Check if the current page is in the allowed pages array
    switch ($_SESSION['user_type']){
        case "Admin":
            $isAllowed = in_array($currentPage, array_merge($admin_pages,$seller_pages));
            break;
        case 'Seller':
            $isAllowed = in_array($currentPage,$seller_pages);
    }
    return $isAllowed;
}
?>