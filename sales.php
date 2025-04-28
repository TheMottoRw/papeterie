<?php
// sales_form.php
include('db.php');
// Insert Sale into Sales Table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $item_id = $_POST['item_id'];
    $quantity_sold = $_POST['quantity'];

    // Step 1: Retrieve item details (buying_price, selling_price, and available_quantity) from items table
    $query = "SELECT buying_price, selling_price, quantity FROM items WHERE id = :item_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':item_id' => $item_id]);

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Step 2: Check if the item has enough quantity in stock
        if ($item['quantity'] >= $quantity_sold) {
            // Retrieve the buying price, selling price, and available quantity
            $buying_price = $item['buying_price'];
            $selling_price = $item['selling_price'];
            $available_quantity = $item['quantity'];

            // Step 3: Perform calculations for total price and profit
            $total_price = $selling_price * $quantity_sold;
            $profit = ($selling_price - $buying_price) * $quantity_sold;

            // Step 4: Insert the sale record into the sales table
            $insertSaleQuery = "INSERT INTO sales (item_id, buying_price, selling_price, quantity, total_price, profit) 
                                VALUES (:item_id, :buying_price, :selling_price, :quantity, :total_price, :profit)";
            $stmt = $pdo->prepare($insertSaleQuery);
            $stmt->execute([
                ':item_id' => $item_id,
                ':buying_price' => $buying_price,
                ':selling_price' => $selling_price,
                ':quantity' => $quantity_sold,
                ':total_price' => $total_price,
                ':profit' => $profit
            ]);

            // Step 5: Update the available quantity in the items table
            $new_quantity = $available_quantity - $quantity_sold;
            $updateItemQuery = "UPDATE items SET quantity = :new_quantity WHERE id = :item_id";
            $stmt = $pdo->prepare($updateItemQuery);
            $stmt->execute([
                ':new_quantity' => $new_quantity,
                ':item_id' => $item_id
            ]);

            echo "Sale recorded successfully!";
        } else {
            // If there's not enough quantity
            echo "Not enough stock available for the sale!";
        }
    } else {
        echo "Item not found!";
    }
}
// Fetch items to populate the item_id dropdown
$query = "SELECT id, name FROM items";
$stmt = $pdo->query($query);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch items to populate the item_id dropdown
$query = "SELECT s.*,i.name as product_name FROM sales s INNER JOIN items i ON i.id = s.item_id";
$stmt = $pdo->query($query);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flexd">
<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>

<!-- Main Content Area -->
<div class="container-fluid" style="margin-left: 260px;">
    <h1>Record Sale</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="item_id" class="form-label">Item</label>
            <select name="item_id" class="form-control" id="item_id"  onchange="setBuyingSellingPrice()" required>
                <option value="">Select an Item</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="buying_price" class="form-label">Buying Price</label>
            <input type="number" name="buying_price" class="form-control" id="buying_price" readonly required>
        </div>

        <div class="mb-3">
            <label for="selling_price" class="form-label">Selling Price</label>
            <input type="number" name="selling_price" class="form-control" id="selling_price" readonly required>
        </div>
        <div class="mb-3">
            <label for="available_quantity" class="form-label">Available quantity</label>
            <input type="number" name="available_quantity" class="form-control" id="available_quantity" readonly required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" id="quantity" required>
        </div>
        <div class="mb-3">
            <label for="total_price" class="form-label">Total price</label>
            <input type="number" name="total_price" class="form-control" id="total_price" readonly required>
        </div>

        <button type="submit" class="btn btn-primary">Record Sale</button>
    </form>
</div>
<div class="container mt-5">
    <h1>Sales</h1>
    <!-- Search Box -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search...">
    </div>
    <table class="table" id="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Buying price</th>
            <th>Selling price</th>
            <th>Total price</th>
            <th>Profit</th>
            <th>Sold on</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?php echo htmlspecialchars($sale['id']); ?></td>
                <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                <td><?php echo htmlspecialchars($sale['buying_price']); ?> RWF</td>
                <td><?php echo htmlspecialchars($sale['selling_price']); ?> RWF</td>
                <td><?php echo htmlspecialchars($sale['total_price']); ?> RWF</td>
                <td><?php echo htmlspecialchars($sale['profit']); ?> RWF</td>
                <td><?php echo htmlspecialchars($sale['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('quantity').addEventListener('keyup', calculateTotal);
    document.getElementById('buying_price').addEventListener('keyup', calculateTotal);
    document.getElementById('selling_price').addEventListener('keyup', calculateTotal);

    function calculateTotal() {
        console.log("Calculate total");
        var buyingPrice = parseFloat(document.getElementById('buying_price').value) || 0;
        var sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
        var quantity = parseInt(document.getElementById('quantity').value) || 0;

        var totalPrice = sellingPrice * quantity;
        var profit = (sellingPrice - buyingPrice) * quantity;
        document.querySelector("#total_price").value = totalPrice;

        // Optionally, you can display the total price and profit to the user
        console.log("Total Price: " + totalPrice);
        console.log("Profit: " + profit);
    }
    function setBuyingSellingPrice(){
        fetch(`http://localhost/papeterie/api.php?action=getItemById&id=${document.querySelector("#item_id").value}`)
            .then(res=>res.json())
            .then(result=>{
                console.log(result);
                document.querySelector("#buying_price").value = parseInt(result.buying_price);
                document.querySelector("#selling_price").value = parseInt(result.selling_price);
                document.querySelector("#available_quantity").value = parseInt(result.quantity);
            })
            .catch(reason => console.log(reason));
    }
</script>
</body>
</html>
