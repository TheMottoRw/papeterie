<?php
include dirname(__DIR__)."/php/invoices.php";
include dirname(__DIR__)."/php/receipt.php";
$invoice = new Invoices();
$receipt = new Receipt();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        switch ($_POST['cate']) {
            case 'create':
                echo $invoice->createInvoice(array("clientid" => $_POST['clientId'],"name" => $_POST['name'], "phone" => $_POST['phone']));
                break;
            case 'pay':
                echo $invoice->payInvoice($_POST);
                break;
            case 'additem':
                echo $invoice->addInvoiceDetails($_POST);
                break;
            case 'deleteinvoice':
                echo $invoice->deleteInvoice(array("invoice_id" => $_POST["id"]));
                break;
            case 'deleteinvoicedetail':
                echo $invoice->deleteInvoiceDetails(array("detail_id" => $_POST["id"]));
                break;
            case 'deletepayment':
                echo $invoice->deletePayment($_POST);
                break;
        }
        break;
    case'GET':
        switch ($_GET['cate']) {
            case 'next':
                echo $invoice->pendingInvoice(array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate']));
                break;
            case 'load':
                header("Content-Type:application/json");
                echo $invoice->getInvoices($_GET);
                break;
            case 'loadbyid':
                header("Content-Type:application/json");
                echo $invoice->getInvoices(array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "invoiceid" => $_GET['invoiceid']));
                break;
            case 'loadlast':
                header("Content-Type:application/json");
                echo $invoice->getLastInvoice(array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate']));
                break;
            case 'payments':
                header("Content-Type:application/json");
                echo $invoice->getInvoicePayments($_GET);
                break;
            case 'loadtotalamount':
                header("Content-Type:application/json");
                echo $invoice->getTotalInvoicesAmount(array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "invoiceid" => $_GET['invoiceid']));
                break;
            case 'loaddetails':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetails($_GET);
                break;
            case 'loaddetailsreport':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetails($_GET);
                break;
            case 'loaddetailbyid':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetailsById($_GET['invoicedtid']);
                break;
            case 'getsalesbyday':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetailsByRange("day", array("sessid" => $_GET['sessid'], "usercate" => $_GET['usercate'], "prodid" => $_GET['prodid']));
                break;
            case 'getsalesbyweek':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetailsByRange("week", array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "prodid" => $_GET['prodid']));
                break;
            case 'getsalesbymonth':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetailsByRange("month", array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "prodid" => $_GET['prodid']));
                break;
            case 'getsalesbyrange':
                header("Content-Type:application/json");
                echo $invoice->getInvoicesDetailsByRange("custom", array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "start" => $_GET['start'], "end" => $_GET['end'], "prodid" => $_GET['prodid']));
                break;
            case 'searchinvoice':
                header("Content-Type:application/json");
                echo $invoice->searchInvoices(array("sessid" => decodeGetParams($_GET['sessid']), "usercate" => $_GET['usercate'], "key" => $_GET['key']));
                break;
            case 'receipt':
//                include "../dev-plugins/file.php";
//                $_GET['sessid'] = decodeGetParams($_GET['sessid']);
                $data = $invoice->getReceipt($_GET);
                $data['filename'] = "../receipts/" . $data['filename'];
                $data['mode'] = 100;
                $data['size'] = array(100, 180);
                if (file_exists($data['filename'])) {
                    unlink($data['filename']);
                }
//		to_pdf($data);co
                $receipt->exportPdf($data);
                break;
            default:
                echo "Invalid";
        }
        break;
}
?>