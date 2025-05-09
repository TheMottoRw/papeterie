

function requiresLogin(){
    var token = document.querySelector("#user-id").value;
    if(token==null){
        window.location.href = baseUrl;
    }
}
function enforceRouteGuard(allowed,route){
    console.log(route);
    console.log(allowed);
    if (allowed.indexOf(route) === -1) {
        localStorage.clear();
        window.location = 'login.php';
    }
}

function getLastRoute(){
    var url = document.location.pathname.split("/")
    var lastSegment = url[url.length - 1];
    if (!isNaN(lastSegment)) {
        url.pop();
        lastSegment = url[url.length - 1];
    }
    return lastSegment.split('?')[0]; // Remove query parameters
}

function routeGuard() {
    requiresLogin();
    var adminUnique = ["users.php","edit_user.php", "report_sales.php","report_debt.php","report_expenses.php","report_items.php","report_outofstock_items.php","report_services.php"];
    var sellerUnique = ["items.php","edit_item.php","services.php","edit_service.php","expenses.php","edit_expenses.php","invoices.php"];
    var url = window.location.pathname.split("/")
    var lastSegment = url[url.length - 1];
    if (!isNaN(lastSegment)) url.pop();
    var lastRoute = url.join('/').split('?')[0]; // Remove query parameters
    var user_type = document.querySelector("#user-type").value;
    console.log(lastSegment);
    console.log(user_type);

    if (user_type !== '') {
        switch (user_type) {
            case "Admin":
                enforceRouteGuard(adminUnique.concat(sellerUnique),lastSegment);
                document.getElementById("menu-user").style.display="block";
                document.getElementById("menu-report").style.display="block";

                break;
            case "Seller":
                enforceRouteGuard(sellerUnique,lastSegment);
                document.getElementById("menu-item").style.display="block";
                document.getElementById("menu-service").style.display="block";
                document.getElementById("menu-expense").style.display="block";
                document.getElementById("menu-sale").style.display="block";

                document.getElementById("menu-user").style.display="none";
                document.getElementById("menu-report").style.display="none";
                break;
            default:
                // window.location = 'login.php';
                break;
        }
    }
}
window.addEventListener('DOMContentLoaded', function () {
    routeGuard();
    // Filter function to search the tables
    var searchBoxEl = document.getElementById('searchBox');
    if(searchBoxEl!=null) {
        document.getElementById('searchBox').addEventListener('keyup', function () {
            var searchTerm = this.value.toLowerCase();

            // Filter Sales Table
            var salesTable = document.getElementById('table');
            var salesRows = salesTable.getElementsByTagName('tr');
            for (var i = 1; i < salesRows.length; i++) { // Skip the header row
                var row = salesRows[i];
                var cells = row.getElementsByTagName('td');
                var rowText = Array.from(cells).map(cell => cell.innerText.toLowerCase()).join(' ');

                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

})