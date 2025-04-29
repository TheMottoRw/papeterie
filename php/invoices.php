<?php
session_start();
date_default_timezone_set("Africa/Kigali");

include_once dirname(__DIR__) . "/php/DatabaseConnection.php";
include_once dirname(__DIR__) . "/php/utils.php";
include_once dirname(__DIR__) . "/php/clients.php";

class Invoices
{
    public $db;
    public $client;
    public $prefix;

    function __construct()
    {
        $this->db = DatabaseConnection::getInstance();
        $this->client = new Clients();
        $this->prefix = 'INV' . date("ymd");
    }

    function adminExistance()
    {//add an Admin if not Exist
        global $conn;
        global $spurl;
        $qr = $this->db->prepare("SELECT * FROM users 
                       WHERE category=:admin AND delete_status=:delstatus");
        $qr->execute(array("admin" => 1, "delstatus" => 0));
        if ($qr->rowCount() == 0) {
            try {
                $this->db->beginTransaction();
                $qy = $this->db->prepare("INSERT INTO users(names,username,email,phone,password,address,category,delete_status,delete_reason,doneby,delete_date) VALUES(:name,:uname,:email,:phone,:password,:address,:cate,:delstatus,:delreason,:doneby,:deldate)");
                $qy->execute(array("name" => 'Admin Admin', "uname" => 'adminstrator', "email" => 'asua@yopmail.com', "phone" => '0726183049', "password" => encryptPwd('12345'), "address" => 'Headquarter', ":cate" => '1', ":delstatus" => 0, ":delreason" => '', ":doneby" => 0, ":deldate" => date("Y-m-d H:i:s")));
//      echo "<br/>".json_encode($qy->errorInfo());
                $this->db->commit();
            } catch (PDOException $ex) {
                echo $ex->getMessage() . "FFFFF";
                $this->db->rollBack();
            }
        }
    }

    function createInvoice($datas)
    {
        $date = date("Y-m-d H:i:s");
        $datas['invoiceid'] = null;
        $newInvoiceIdentifier = $this->pendingInvoice($datas);
        //Register client info
//echo var_dump(addClient($datas));
        $clientId = $datas['clientid'];
        if ($datas['clientid'] == '0' || $datas['clientid'] == '' || $datas['clientid'] == 'null' || $datas['clientid'] == 'undefined') {
            $clientInfo = $this->db->selectOne("SELECT * FROM clients where phone=?", [$datas['phone']]);
            if ($clientInfo == null) {
                $clientId = $this->client->insert(['name' => $datas['name'], 'phone' => $datas['phone']]);
            } else $clientId = $clientInfo['id'];
        }
        $invoiceExist = $this->db->count("SELECT * FROM invoices WHERE invoice_identifier=?", [$newInvoiceIdentifier]);
        if ($invoiceExist == 0) {//avoid duplication of invoiceid in the same shops
            $qy = $this->db->insert("INSERT INTO invoices(invoice_clientid,invoice_identifier) VALUES(?,?)", [$clientId, $newInvoiceIdentifier]);;
            if ($qy) {
                $feed = "ok";
            } else {
                $feed = "fail";
            }
        } else {
            $feed = 'exist';
        }
        return $feed;
    }

    function payInvoice($datas)
    {
        $invoice = $this->db->selectOne("SELECT * FROM invoices where invoice_id=?", [$datas['invoiceid']]);
        if ($invoice == false) return 'notfound';
        //record payment history
        $payment = $this->db->insert("INSERT INTO payments(invoice_id,paid) VALUES(?,?)", [$datas['invoiceid'], $datas['paid']]);
        //update remaining amount
        $remain = $invoice['remain'] - $datas['paid'];
        $total_paid = $invoice['paid'] + $datas['paid'];
        $updateInvoice = $this->db->update("UPDATE invoices SET paid=?,remain=? WHERE invoice_id=?", [$total_paid, $remain, $datas['invoiceid']]);
        if ($payment) return 'ok';
        return 'fail';
    }

    function getInvoicePayments($datas)
    {
        $query = "SELECT p.*,i.invoice_identifier,i.paid as invoice_paid,i.remain as invoice_remain,c.id AS client_id,c.name AS client_name,c.phone AS client_phone
                    FROM payments p INNER JOIN invoices i ON p.invoice_id = i.invoice_id
                    INNER JOIN clients c ON c.id = i.invoice_clientid WHERE p.invoice_id = ?";
        $data = $this->db->select($query, [$datas['invoice']]);
        return json_encode($data);
    }

    function getLastInvoice($datas)
    {
        $data = $this->db->selectOne("SELECT * FROM invoices ORDER BY invoice_id DESC LIMIT 0,1");
        return json_encode($data);
    }

    function pendingInvoice($datas)
    {
        $invoIdent = date("Y-m-d");
        $pattern = '^' . $this->prefix . '-[0-9]+$';
        $stmt = $this->db->selectOne("
            SELECT invoice_identifier 
            FROM invoices 
            WHERE invoice_identifier REGEXP ?
            ORDER BY invoice_id DESC 
            LIMIT 1
        ", [$pattern]);

        if ($stmt) {
            $ide = explode("-", $stmt['invoice_identifier'])[1] + 1;
            return $this->prefix . "-" . $ide;
        }
        return $this->prefix . "-1";
    }

//pendingInvoice();
    function getInvoices($datas)
    {
        $query = "SELECT i.*,c.name as client_name,c.phone as client_phone FROM invoices i INNER JOIN clients c ON c.id=i.invoice_clientid WHERE i.delete_status=? ORDER BY i.invoice_id DESC";
        if(isset($datas['request']) && $datas['request']=='report'){
            $query = "SELECT i.*,c.name as client_name,c.phone as client_phone FROM invoices i INNER JOIN clients c ON c.id=i.invoice_clientid WHERE i.delete_status=? AND i.total_amount!=0 ORDER BY i.invoice_id DESC";
        }else if(isset($datas['request']) && $datas['request']=='report_debt'){
            $query = "SELECT i.*,c.name as client_name,c.phone as client_phone FROM invoices i INNER JOIN clients c ON c.id=i.invoice_clientid WHERE i.delete_status=? AND i.total_amount!=0 AND i.total_amount!=i.paid AND i.regdate BETWEEN '".$datas['start_date']."' AND  '".$datas['end_date']."' ORDER BY i.invoice_id DESC";
        }
        $data = $this->db->select($query, [0]);
        return json_encode($data);
    }

    function getInvoiceById($id)
    {
        $data = $this->db->selectOne("SELECT i.*,c.name as client_name,c.phone as client_phone FROM invoices i INNER JOIN clients c ON c.id=i.invoice_clientid WHERE i.delete_status=? AND i.invoice_id=? ORDER BY i.invoice_id DESC", [0, $id]);
        return json_encode($data);
    }

    function addInvoiceDetails($datas)
    {
        // Get the form data
        $invoice_id = $datas['invoice_id'];
        $item_id = $datas['item_id'];
        $quantity_sold = (float)$datas['quantity'];
        $selling_price = $datas['selling_price'];


        // Step 1: Retrieve item details (buying_price, selling_price, and available_quantity) from items table
        $item = $this->db->selectOne("SELECT buying_price, selling_price, quantity FROM items WHERE id = ?", [$item_id]);
        if ($item == false) return 'notfound';
        // Step 2: Check if the item has enough quantity in stock
//        echo json_encode($item)."::".$quantity_sold;
        if ((float)$item['quantity'] < $quantity_sold) return 'notenough';
        // Retrieve the buying price, selling price, and available quantity
        $buying_price = $item['buying_price'];
        $available_quantity = $item['quantity'];

        // Step 3: Perform calculations for total price and profit
        $total_price = $selling_price * $quantity_sold;
        $profit = ($selling_price - $buying_price) * $quantity_sold;

        // Step 4: Insert the sale record into the sales table
        $sell = $this->db->insert("INSERT INTO invoicesdetails (invoice_id,item_id, buying_price, selling_price, quantity, total_price, profit) 
                                VALUES (?,?,?,?,?,?,?)", [$invoice_id, $item_id, $buying_price, $selling_price, $quantity_sold, $total_price, $profit]);

        // Step 5: Update the available quantity in the items table
        $new_quantity = $available_quantity - $quantity_sold;
        $updateItemQuery = "UPDATE items SET quantity = ? WHERE id = ?";
        $stmt = $this->db->update($updateItemQuery, [$new_quantity, $item_id]);;
        //Step 6: update total invoice amount
        $invoice = $this->db->selectOne("SELECT * FROM invoices WHERE invoice_id=?", [$invoice_id]);
        $invoiceAmount = $this->getTotalInvoicesAmount(['invoiceid' => $invoice_id]);
        $remainAmount = $invoiceAmount['total_amount'] - $invoice['paid'];
        $updateItemQuery = "UPDATE invoices SET remain=?,total_amount = ?,total_profit=? WHERE invoice_id = ?";
        $stmt = $this->db->update($updateItemQuery, [$remainAmount, $invoiceAmount['total_amount'], $invoiceAmount['total_profit'], $invoice_id]);

        return "ok";
    }

    function getTotalInvoicesAmount($datas)
    {
        $amounts = $this->db->selectOne("SELECT sum(invoicesdetails.total_price) as total_amount,sum(invoicesdetails.profit) as total_profit FROM invoices,invoicesdetails WHERE invoices.invoice_id=invoicesdetails.invoice_id AND invoices.invoice_id=? AND invoices.delete_status=0 AND invoicesdetails.status='sold'", [$datas['invoiceid']]);
        return $amounts != false ? $amounts : ['total_amount' => 0, 'total_profit' => 0];
    }

    function deleteInvoice($datas)
    {
        $invoice_id = $datas['invoice_id'];
        //check its existance
        $invoice = $this->db->selectOne("SELECT * FROM invoices WHERE invoice_id=?", [$invoice_id]);
        if ($invoice == false) return 'notfound';
        //return all invoicedetails quantity back to items,and delete all invoice details
        $details = $this->db->select("SELECT * FROM invoicesdetails WHERE invoice_id=?", [$invoice_id]);
        foreach ($details as $detail) {
            $item = $this->db->selectOne("SELECT * FROM items WHERE id=?", [$detail['item_id']]);
            $new_quantity = $item['quantity'] + $detail['quantity'];
            $stmt = $this->db->update("UPDATE items SET quantity = ? WHERE id = ?", [$new_quantity, $detail['item_id']]);
            if ($stmt == 0) continue;
            //delete invoice detail
            $deldetail = $this->db->update("UPDATE invoicesdetails SET status='deleted' WHERE invoicedt_id=?", [$detail['invoicedt_id']]);
        }
        //reset payments to paid zero and remain to total amount
        $payment = $this->db->update("UPDATE payments SET paid=0 WHERE invoice_id=?", [$invoice_id]);
        //update invoice delete status to deleted and paid to 0 and remain to total amount
        $deldetails = $this->db->update("UPDATE invoices SET paid=0,remain=?,delete_status=1 WHERE invoice_id=?", [$invoice['total_amount'], $invoice_id]);
        if ($deldetails == 0) return 'fail';
        //update invoice delete status to deleted
        return 'ok';
    }

    function deleteInvoiceDetails($datas)
    {
        $invoicedt_id = $datas['detail_id'];
        $detail = $this->db->selectOne("SELECT * FROM invoicesdetails WHERE invoicedt_id=?", [$invoicedt_id]);
        if ($detail == false) return 'notfound';
        $item = $this->db->selectOne("SELECT * FROM items WHERE id=?", [$detail['item_id']]);
        $new_quantity = $item['quantity'] + $detail['quantity'];
        $stmt = $this->db->update("UPDATE items SET quantity = ? WHERE id = ?", [$new_quantity, $detail['item_id']]);
        if ($stmt == 0) return 'fail';
        //dedact in invoice total amount from the amount of detail
        $invoice = $this->db->selectOne("SELECT * FROM invoices WHERE invoice_id=?", [$detail['invoice_id']]);
        $new_total_amount = $invoice['total_amount'] - $detail['total_price'];
        $new_remain_amount = $new_total_amount - $invoice['paid'];;
        $updinvoice = $this->db->update("UPDATE invoices SET remain=?,total_amount=? WHERE invoice_id=?", [$new_remain_amount, $new_total_amount, $invoice['invoice_id']]);
        if ($updinvoice == 0) return 'fail';
        //delete invoice detail
        $deldetails = $this->db->update("UPDATE invoicesdetails SET status='deleted' WHERE invoicedt_id=?", [$detail['invoicedt_id']]);
        if ($deldetails == 0) return 'fail';
        return 'ok';
    }

    function deletePayment($datas)
    {
        $id = $datas['id'];
        // Pre-validate data before starting transaction
        $payment = $this->db->selectOne("SELECT * FROM payments WHERE id=?", [$id]);
        if ($payment == false) return 'notfound';
        $invoice = $this->db->selectOne("SELECT * FROM invoices WHERE invoice_id=?", [$payment['invoice_id']]);
        if ($invoice == false) return 'notfound';

        try {
            $this->db->getConnection()->beginTransaction();
            // Calculate new amounts
            $new_paid_amount = $invoice['paid'] - $payment['paid'];
            $new_remain_amount = $invoice['remain'] + $payment['paid'];
            // Update invoice amounts
            $updateInvoice = $this->db->update("UPDATE invoices SET paid=?, remain=? WHERE invoice_id=?",[$new_paid_amount, $new_remain_amount, $payment['invoice_id']]
            );

            if ($updateInvoice === 0) throw new Exception("Failed to update invoice");
            // Update payment status
            $updatePayment = $this->db->update("UPDATE payments SET delete_status=1 WHERE id=?",[$id]);
            if ($updatePayment === 0) throw new Exception("Failed to update payment");
            $this->db->getConnection()->commit();
            return 'ok';
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            return 'fail';
        }
    }

    function searchInvoices($datas)
    {
        global $conn;
        global $spurl;
        $data = null;
        $qy = $this->db->prepare("SELECT * FROM invoices WHERE delete_status=:delstatus AND invoice_identifier LIKE :key");
        $qy->execute(array("key" => $datas['key'] . '%', "delstatus" => 0));
        $data = $qy->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($data);
    }

    function getInvoicesDetails($datas)
    {

        $query = "SELECT id.*,i.invoice_identifier,i.paid,i.remain,c.id AS client_id,c.name AS client_name,c.phone AS client_phone,it.name as item_name 
                    FROM invoicesdetails id INNER JOIN invoices i ON i.invoice_id = id.invoice_id
                    INNER JOIN clients c ON c.id = i.invoice_clientid
                    INNER JOIN items it ON it.id = id.item_id
                    WHERE i.invoice_id = ? AND i.delete_status=0 AND id.status='sold'";
        $data = $this->db->select($query, [$datas['invoice']]);
        return json_encode($data);
    }

    function getInvoicesDetailsById($invoiceDtId)
    {
        global $conn;
        global $spurl;
        $data = null;

        $data = $this->db->selectOne("SELECT * FROM invoicesdetails WHERE invoicedt_id=?", [$invoiceDtId]);;
        return json_encode(array("details" => $data));

    }

    function getInvoicesDetailsByRange($cate, $range)
    {
        global $conn;
        global $spurl;
        $data = null;
        $prodid = ($range["prodid"] == 'Select Product' ? false : true);
        $shopid = checkOwner($range);
        switch ($cate) {
            case'day':
                $day = date("Y-m-d");
                $start = $day;
                $end = $day . " 23:59:59";

                break;
            case'week':
                $week = weekRange();
                $start = $week["start"];
                $end = $week["end"] . " 23:59:59";

                break;
            case'month':
                $month = monthRange();
                $start = $month["start"];
                $end = $month["end"] . " 23:59:59";
                break;
            case'custom':
                $start = $range["start"];
                $end = $range["end"] . " 23:59:59";

                break;
            default:
        }
        $qy = $conn->prepare("");
        $qy->execute(array("start" => $start, "ends" => $end, "shopid" => $shopid));
        $data = $qy->fetchAll(PDO::FETCH_ASSOC);
        return json_encode(array("details" => $data));
    }


    function getReceipt($datas)
    {
        $details = json_decode($this->getInvoicesDetails(['invoice' => $datas['invoiceid']]), TRUE);
        $totPayment = 0;
        $shopInfo = $this->db->selectOne("select * from users", []);
        $invoiceInfo = json_decode($this->getInvoiceById($datas['invoiceid']), TRUE);
        $receipt = "<div id='receipt' style='width: 330px;border:2px solid black;font-family:arial;font-size: 12px;padding-left:5px;padding-top:7px;padding-bottom:7px;padding-right:5px;'>";

//        echo json_encode($details);exit;
        $receipt .= "<div id='receiptHeader'>Seller:<b>" . $shopInfo['name'] . "</b><br> Phone:" . $shopInfo['email'] . "<hr></b> Customer:" . $invoiceInfo['client_name'] . "<br>Cust. phone:" . $invoiceInfo['client_phone'] . "<br>Invoice:" . $invoiceInfo['invoice_identifier'] . "<hr></div>";
        $receipt .= "<table border='0' style='cell-padding:3px;font-size:12px;'>";
        $receipt .= "<tr class='receiptBody' style='background-color:lightgreen'><td>Product</td><td>Quantity</td><td>Pu</td><td>Total</td></tr>";
        foreach ($details as $data) {
            $receipt .= "<tr class='receiptBody'><td>" . $data['item_name'] . "</td><td>" . $data['quantity'] . "</td><td>" . $data['selling_price'] . " RWF</td><td>" . $data['total_price'] . " RWF</td></tr>";
        }
        $receipt .= "<tr class='receiptFooter' style='background-color:lightgray'><td></td><td>Paid:" . ($invoiceInfo['paid'] == null ? '0' : $invoiceInfo['paid']) . " RWF</td><td>Remain:" . ($invoiceInfo['paid'] == null ? $totPayment : $invoiceInfo['remain']) . " RWF</td><td>" . $invoiceInfo['total_amount'] . " RWF</td></tr>";
        $receipt .= "</table>";
        $receipt .= "<br>&nbsp;&nbsp;&nbsp;<center><font color='grey'><i>Printed &nbsp;&nbsp;&nbsp;On " . date("Y-m-d") . " At " . date("H:i:s") . "</i></font></center>";
        $receipt .= "</div>";
        return array("filename" => "Receipt" . $invoiceInfo[0]['invoice_identifier'], "contents" => $receipt);
    }
}

?>