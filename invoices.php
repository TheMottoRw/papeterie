<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>
    <!-- Bootstrap CSS -->
    <!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add these in the head section -->
    <link href="assets/select2/select2.min.css" rel="stylesheet"/>
    <link href="assets/select2/select2-bootstrap-5-theme.min.css"
          rel="stylesheet"/>
</head>
<body>

<?php
include('sidebar.php');
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Recent Invoices</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
            Create Invoice
        </button>
    </div>
    <div id="loader"></div>
    <input type="hidden" id="invoiceid">
    <input type="hidden" id="invoicedetailid">
    <input type="hidden" id="action">
    <!-- Invoice Table -->
    <!-- Search Box -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search...">
    </div>
    <div id="responseSuccess"></div>
    <div id="responseFail"></div>
    <table class="table table-striped" id="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Invoice ID</th>
            <th>Client Name</th>
            <th>Phone</th>
            <th>Paid Amount</th>
            <th>Remain Amount</th>
            <th>Total Amount</th>
            <th>Creation Date</th>
        </tr>
        </thead>
        <tbody id="table-data">
        <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="response"></div>
                <form id="invoiceForm">
                    <!-- Replace the existing select with this -->
                    <div class="mb-3">
                        <label class="form-label">Client Information</label><br>
                        <select id="clients" class="form-control" style="width: 100%;">
                            <option value="">Select a fruit</option>
                            <option value="apple">Apple</option>
                            <option value="banana">Banana</option>
                            <option value="cherry">Cherry</option>
                            <option value="date">Date</option>
                            <option value="grape">Grape</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnCreateInvoice"  data-bs-dismiss="modal">Save Invoice</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="saveCreatedInvoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add item to invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="margin-left: 3.5%">
                <p id="regInvoiceDtResponse"></p>
                <div class="row">
                    <input type="hidden" id="invid">
                    <input type="hidden" id="invboughtpu">
                    <div id="add-detail-response"></div>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="items" class="form-label">Item</label>
                            <select name="items" class="form-control" id="items" required>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="buying_price" class="form-label">Buying Price</label>
                            <input type="number" name="buying_price" class="form-control" id="buying_price" readonly
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" name="selling_price" class="form-control" id="selling_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="available_quantity" class="form-label">Available quantity</label>
                            <input type="number" name="available_quantity" class="form-control" id="available_quantity"
                                   readonly required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" id="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total price</label>
                            <input type="number" name="total_price" class="form-control" id="total_price" readonly
                                   required>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnAddInvoiceDetail" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateCreatedInvoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add item on invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="margin-left: 3.5%">
                <p id="updInvoiceDtResponse"></p>
                <div class="row">
                    <input type="hidden" id="updinvdtid">
                    <input type="hidden" id="updinvid">
                    <input type="hidden" id="updinvstockid">
                    <div class="col-lg-6">
                        <label>Products </label>
                        <select class="form-control" id="updinvoiceProdLoad">
                        </select>
                        <label>Quantity </label>
                        <input type="number" name="updquantity" id="updinvquantity" class="form-control"
                               placeholder="Quantity" pattern="[0-9]">
                        <label>Selling PU </label>
                        <input type="number" onkeyup="updautoCalculate()" name="updsellPU" id="updsellPU"
                               class="form-control" pattern="[0-9]">
                    </div>
                    <div class="col-lg-6">
                        <div><br>
                            <label>Selected Product:<span id="updselProduct"></span></label><br>
                            <label>Range PU:<span id="updrangePU"></span></label><br>
                            <label>Bought PU:<span id="updselBoughtPU"></span></label><br>
                            <label>Expiration Date:<span id="updselExpdate"></span></label><br>
                            <label>Sell PU:<span id="updselSellPU"></span></label><br>
                            <label>Availalble Qty:<span id="updavQty"></span></label><br>
                            <label>Quantity:<span id="updselQuantity"></span></label><br>
                            <label>Payment:<span id="updselPayment"></span></label><br>
                            <label>Expected Profit:<span id="updselProfit"></span></label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnUpdInvoiceDetail"  data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentInvoicesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make payment of an invoice <span id="invoicesIdent"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="clpid">
                <input type="hidden" id="cpinvoiceid">
                <input type="hidden" id="cpclientid">
                <p id="regPayinfoResponse"></p>
                <h5 style="text-align: center">Exchange Calculator</h5>
                <label>Given Amount</label>
                <input type="number" name="given" class="form-control" id="given">
                <label>Exchange Amount</label>
                <input type="number" name="echange" id="echange" class="form-control" readonly="readonly">
                <h5 style="text-align: center">Payment Amount</h5>
                <label>Total Amount</label>
                <input type="text" name="totalamount" class="form-control" id="totalamount" readonly="readonly">
                <label>Paid Amount</label>
                <input type="hidden" name="paidhidamount" id="paidhidamount" class="form-control">
                <input type="text" name="paidamount" id="paidamount" class="form-control">
                <label>Credit Remain</label>
                <input type="hidden" name="remainhidamount" id="remainhidamount" class="form-control"
                       readonly="readonly">
                <input type="text" name="remainamount" id="remainamount" class="form-control" readonly="readonly">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnPayInvoice" data-bs-dismiss="modal">Pay</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updpaymentInvoicesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updpayInvModalTitle">Update Payment Invoices: <span
                            id="updinvoicesIdent"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="clcpid">
                <p id="updPayinfoResponse"></p>
                <label>Total Amount</label>
                <input type="text" name="updtotalamount" class="form-control" id="updtotalamount" readonly="readonly">
                <label>Paid Amount</label>
                <input type="hidden" name="updpaidhidamount" id="updpaidhidamount" class="form-control">
                <input type="text" name="updpaidamount" id="updpaidamount" class="form-control">
                <label>Credit Remain</label>
                <input type="hidden" name="updremainhidamount" id="updremainhidamount" class="form-control"
                       readonly="readonly">
                <input type="text" name="updremainamount" id="updremainamount" class="form-control" readonly="readonly">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnUpdInvoiceDetail" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewCreatedInvoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceidentViewModalTilt"> Invoice <span id="invoiceIdentTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <p style="color:green;font-size:14px;"></p>
                    <table class="table table-bordered" id="tblInvDt">
                        <caption>Invoices Details &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IDENTIFIIER:<span
                                    id="invidt" style="font-weight: bold"></span>&nbsp;&nbsp;&nbsp;<span id="totalprof"
                                                                                                         class="pull-right"
                                                                                                         style="margin-right:3%;"></span>
                        </caption>
                        <thead>
                        <tr>
                            <th>#Counts</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Buying price</th>
                            <th>Sell Price</th>
                            <th>Total amount</th>
                            <th>Profit</th>
                            <th> Date</th>
                            <th colspan="3"> Modifications</th>
                        </tr>
                        </thead>
                        <tbody id="table-details-data">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnUpdInvoiceDetail" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewPaymentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentsModalTiltle"> Invoice payment made <span
                            id="invoiceIdentTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <p style="color:green;font-size:14px;"></p>
                    <table class="table table-bordered" id="tblInvDt">
                        <caption>Invoices Details &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IDENTIFIIER:<span
                                    id="invpayidt" style="font-weight: bold"></span>&nbsp;&nbsp;&nbsp;<span
                                    id="totalprof" class="pull-right"
                                    style="margin-right:3%;"></span></caption>
                        <thead>
                        <tr>
                            <th>#Counts</th>
                            <th>Paid</th>
                            <th> Date</th>
                            <th> Action</th>
                        </tr>
                        </thead>
                        <tbody id="table-payments-data">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelCreatedInvoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="invoiceidentViewModalTilt"> Are you Sure you want to Cancel this Product
                    Sld</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cancelId" id="cancelId">
                <p style="font-size:14px;" id="delInvoiceDetailResponse"></p>
                <div class="">
                    <p class="" style="font-size: 14px">Invoice cancelled/removed/refunded quantity get back to
                        items,all transaction will be reset</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnDeleteInvoiceDetail" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="delModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="delModalTitle">Do you want to delete invoice: <span
                            id="invoicesDelIdent"></span></h4>
                <span class="close" data-dismiss="modal">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="delinvid">
                <p id="delInvoiceResponse"></p>
                <p class="" style="font-size: 14px">Item Sold when cancelled/refunded quantity get back to item stock
                    and its transaction get reset</p>

            </div>
            <div class="modal-footer">
                <button type="button" id="btnDeleteInvoice" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-check-square-o"></i>
                    Delete
                </button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa fa-remove"></i>
                    Close
                </button>
            </div>
        </div>

    </div>
</div><!--end delete Modal-->
<div id="deletePaymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deletePaymentModalTitle">Confirm deleting partial payment of the
                    invoice </h4>
                <span class="close" data-dismiss="modal">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="paymentid">
                <p id="deletePaymentResponse"></p>
                <p class="" style="font-size: 14px">By deleting payment of this invoice,remaining unpaid amount will
                    increase </p>

            </div>
            <div class="modal-footer">
                <button type="button" id="btnDeletePayment" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-check-square-o"></i>
                    Delete
                </button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa fa-remove"></i>
                    Close
                </button>
            </div>
        </div>

    </div>
</div><!--end delete Modal-->

<!-- Bootstrap JS and dependencies -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->

<script>
    // document.getElementById('saveInvoice').addEventListener('click', function () {
    //     // Add your save logic here
    //     // You can collect form data and send it to the server
    //     alert('Invoice saved successfully!');
    //     // Close the modal
    //     const modal = bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal'));
    //     modal.hide();
    // });
</script>
<script src="assets/jquery/jquery-3.7.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>

<script src="script.js"></script>
<script src="assets/select2/select2.min.js"></script>
<script src="js/helpers.js"></script>
<script src="js/jqdepend.js"></script>

<script>
    $(document).ready(function () {
        loadClients();
        loadInvoices();
        loadItems();

        function loadClients() {
            ajax('ajax/clients.php?cate=load', {}, 'GET', 'json', function (data) {
                let options = '<option value="">Select a client</option>';
                for (let i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i].id + '">' + data[i].name + ' (' + data[i].phone + ')</option>';
                }
                $("#clients").html(options);

                $('#clients').select2({
                    placeholder: "Search or select an option",
                    dropdownParent: $('#createInvoiceModal'), // the ID of your modal
                    allowClear: true
                });
            })
        }

        function loadItems() {
            ajax('ajax/items.php?cate=load', {}, 'GET', 'json', function (data) {
                let options = '<option value="">Select a client</option>';
                for (let i = 0; i < data.length; i++) {
                    options += `<option value='${data[i].id}' data-obj='${data[i]}' onclick="setBuyingSellingPrice(this)">${data[i].name}</option>`;
                }
                $("#items").html(options);

                // $('#items').select2({
                //     placeholder: "Search or select an option",
                //     dropdownParent: $('#createInvoiceModal'), // the ID of your modal
                //     allowClear: true
                // });
            })
        }

        function loadInvoices() {
            ajax('ajax/invoices.php?cate=load', {}, 'GET', 'json', function (data) {
                setContent(data);
            })
        }

        function loadInvoicesDetails(id) {
            ajax(`ajax/invoices.php?cate=loaddetails&invoice=${id}`, {}, 'GET', 'json', function (data) {
                setContentDetails(data);
            })
        }

        function loadPayments(id) {
            ajax(`ajax/invoices.php?cate=payments&invoice=${id}`, {}, 'GET', 'json', function (data) {
                setContentPayments(data);
            })
        }

        function setContent(arr) {
            let data = '';
            for (let i = 0; i < arr.length; i++) {
                data += `<tr>
                            <td>${i + 1}</td>
                            <td>${arr[i].invoice_identifier}</td>
                            <td>${arr[i].client_name}</td>
                            <td>${arr[i].client_phone}</td>
                            <td>${arr[i].paid}</td>
                            <td>${arr[i].remain}</td>
                            <td>${arr[i].total_amount}</td>
                            <td>${arr[i].regdate.substring(0, 16)}</td>
                    <td style='text-align:center;position:inherit;' class='invoicesmore'>`;
                data += `
                    <a href='#' id='addInvoiceDt' data-action='additem' class='btn btn-outline-primary btn-sm invoice-buttons' data-obj='${JSON.stringify(arr[i])}' data-bs-toggle='modal'  data-bs-target='#saveCreatedInvoiceDetailsModal'>Add</a>
                    <a href='#' id='loadInvoiceDt' data-action='viewdetails' data-obj='${JSON.stringify(arr[i])}' class='btn btn-outline-success btn-sm invoice-buttons' data-bs-toggle='modal'  data-bs-target='#viewCreatedInvoiceDetailsModal' ${arr[i].total_amount == 0 ? 'disabled' : ''}>View</a>
                    <a href='#' id='addInvoicePayment'  data-action='invoicepay'  data-obj='${JSON.stringify(arr[i])}' class='btn btn-outline-info btn-sm invoice-buttons' data-bs-toggle='modal'  data-bs-target='#paymentInvoicesModal' ${arr[i].total_amount == 0 || arr[i].total_amount == arr[i].paid ? 'disabled' : ''}><i class='fa fa-bitcoin'></i> Pay</a>
                    <a href='#' id='viewInvoicePayment' data-action='viewpayments' data-obj='${JSON.stringify(arr[i])}' class='btn btn-outline-secondary btn-sm invoice-buttons' data-bs-toggle='modal'  data-bs-target='#viewPaymentsModal' ${arr[i].paid == 0 ? 'disabled' : ''}><i class='fa fa-eye'></i> Payments</a>
                    <a target='_blank' href='ajax/invoices.php?cate=receipt&invoiceid=${arr[i].invoice_id}&id=printReceipt' data-obj='${JSON.stringify(arr[i])}' class='btn btn-outline-warning btn-sm'${arr[i].total_amount == null ? 'disabled' : ''}>Receipt</a>
                    <a href='#' class='btn btn-outline-danger btn-sm invoice-buttons' data-action='deleteinvoice' data-obj='${JSON.stringify(arr[i])}' data-bs-toggle='modal'  data-bs-target='#delModal'>Delete</a>
                    </a>`
                data += `</td>
                        </tr>`;
            }
            $("#table-data").html(data);
        }

        function setContentPayments(arr) {
            let data = '', total_payment = 0, invoice_id = arr.length > 0 ? arr[0].invoice_identifier : '';
            for (let i = 0; i < arr.length; i++) {
                total_payment += arr[i].paid;

                data += `<tr>
                            <td>${i + 1}</td>
                            <td>${arr[i].paid} RWF</td>
                            <td>${arr[i].created_at.substring(0, 16)}</td>;
                            <td><a href="#" class="btn btn-sm btn-outline-danger invoice-buttons" data-action='deletepayment' data-obj='${JSON.stringify(arr[i])}' data-bs-toggle="modal" data-bs-target="#deletePaymentModal">Delete</td>;
                        </tr>`;
            }
            $("#table-payments-data").html(data);
            $("#invpayidt").html(invoice_id);
            $("#invoiceIdentTitle").html(invoice_id);
            $("#totalprof").html("Total payment: " + total_payment + " RWF");
        }

        function setContentDetails(arr) {
            let data = '', total_profit = 0, invoice_id = arr.length > 0 ? arr[0].invoice_identifier : '';
            for (let i = 0; i < arr.length; i++) {
                total_profit += arr[i].profit;
                data += `<tr>
                            <td>${i + 1}</td>
                            <td>${arr[i].item_name}</td>
                            <td>${arr[i].quantity}</td>
                            <td>${arr[i].buying_price} RWF</td>
                            <td>${arr[i].selling_price} RWF</td>
                            <td>${arr[i].total_price} RWF</td>
                            <td>${arr[i].profit} RWF</td>
                            <td>${arr[i].created_at.substring(0, 16)}</td>
                            <td><a href="#invoices.php?action=cancel&target=details&id=${arr[i]['id']}" class="btn btn-sm btn-outline-danger invoice-buttons" data-action='canceldetails' data-obj='${JSON.stringify(arr[i])}' data-bs-toggle='modal' data-bs-target='#cancelCreatedInvoiceDetailsModal'>Remove</a></td>`;
                data += `</td>
                        </tr>`;
            }
            $("#table-details-data").html(data);
            $("#invidt").html(invoice_id);
            $("#totalprof").html("Total profit: " + total_profit + " RWF");
        }
        

        function createInvoice() {
            ajax('ajax/invoices.php', {
                cate: 'create',
                name: $('#name').val(),
                phone: $('#phone').val(),
                clientId: $('#clients').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Invoice Created Success</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    $("#responseFail").html("<font color='error'>Can not create invoice</font>");
                }
                $('#name').val('');
                $('#phone').val('');
            });
        }

        function makePayment() {
            ajax('ajax/invoices.php', {
                cate: 'pay',
                paid: $('#paidamount').val(),
                remain: $('#remainamount').val(),
                invoiceid: $('#invoiceid').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Invoice paid</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    $("#responseFail").html("<font color='error'>Can not pay invoice</font>");
                }
                $('#paidamount').val('');
                $('#remainamount').val('');
            });
        }

        function addProductOnInvoice() {
            ajax('ajax/invoices.php', {
                cate: 'additem',
                invoice_id: $('#invoiceid').val(),
                item_id: $('#items').val(),
                quantity: $('#quantity').val(),
                selling_price: $('#selling_price').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Item added on invoice Success</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    if (data == 'notenought') {
                        $("#responseFail").html("<font color='error'>There is no enought quantity for the selected item</font>");
                    } else if (data == 'fail') {
                        $("#responseFail").html("<font color='error'>Can not add item on invoice</font>");
                    }
                }
                $('#name').val('');
                $('#phone').val('');
            });
        }

        function deleteInvoice() {
            ajax('ajax/invoices.php', {
                cate: 'deleteinvoice',
                id: $('#invoiceid').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Invoice deleted Success</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    $("#responseFail").html("<font color='error'>Can not delete invoice</font>");
                }
            });
        }

        function deleteInvoiceDetail() {
            ajax('ajax/invoices.php', {
                cate: 'deleteinvoicedetail',
                id: $('#invoicedetailid').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Item remove from invoice Success</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    $("#responseFail").html("<font color='error'>Can not remove item on invoice</font>");
                }
            });
        }

        function deleteInvoicePayment() {
            ajax('ajax/invoices.php', {
                cate: 'deletepayment',
                id: $('#paymentid').val(),
            }, 'POST', 'text', function (data) {
                if (data == 'ok') {
                    $("#responseFail").html("");
                    $("#responseSuccess").html("<font color='green'>Payment deleted success</font>");
                    loadInvoices();
                } else {
                    $("#responseSuccess").html("");
                    $("#responseFail").html("<font color='error'>Can not delete payment</font>");
                }
            });
        }

        function setBuyingSellingPrice() {
            fetch(`http://localhost/papeterie/ajax/items.php?cate=loadbyid&id=${document.querySelector("#items").value}`)
                .then(res => res.json())
                .then(result => {
                    console.log(result);
                    document.querySelector("#buying_price").value = parseInt(result.buying_price);
                    document.querySelector("#selling_price").value = parseInt(result.selling_price);
                    document.querySelector("#available_quantity").value = parseInt(result.quantity);
                })
                .catch(reason => console.log(reason));
        }

        function invoiceButtons(el, obj) {
            $("#invoiceid").val(obj.invoice_id);
            $("#action").val(el.getAttribute("data-action"));
            switch (el.getAttribute("data-action")) {
                case 'viewdetails':
                    loadInvoicesDetails(obj.invoice_id);
                    break;
                case 'viewpayments':
                    loadPayments(obj.invoice_id);
                    break;
                case 'invoicepay':
                    $("#totalamount").val(obj.paid != 0 ? obj.remain : obj.total_amount);
                    // $("#paidamount").val(obj.paid);
                    break;
                case 'deleteinvoice':
                    $("#invoicesDelIdent").val(obj.invoice_identifier);
                    break;
                case 'canceldetails':
                    console.log(obj);
                    $("#invoicedetailid").val(obj.invoicedt_id);
                    break;
                case 'deletepayment':
                    console.log(obj);
                    $("#paymentid").val(obj.id);
                    break;
            }
        }

        function exchangeCalculator() {
            var url = document.URL.replace("#", "").split("/");
            var page = url[url.length - 1];
            switch (page) {
                case'invoices.php':
                    if ($("#paidamount").val() != '') {
                        $("#remainamount").val(parseInt($("#totalamount").val()) - parseInt($("#paidamount").val()));
                        if ($("#given").val() != '') {
                            if (parseInt($("#paidamount").val()) <= parseInt($("#given").val())) {
                                $("#echange").val(parseInt($("#given").val()) - parseInt($("#paidamount").val()));
                            }
                        }
                    }
            break;
        default:
            console.log("invalid");
        }
    }


    //events
    $("#btnCreateInvoice").click(function () {
        createInvoice();
    })
    $("#btnDeleteInvoice").click(function () {
        deleteInvoice();
    })
    $("#btnDeleteInvoiceDetail").click(function () {
        deleteInvoiceDetail();
    })
    $("#btnDeletePayment").click(function () {
        deleteInvoicePayment();
    })
    $("#items").change(function () {
        setBuyingSellingPrice();
    })
    $("#selling_price").keyup(function () {
        $("#total_price").val(Math.abs(parseInt($(this).val()) * parseInt($("#quantity").val())));
    })
    $("#quantity").keyup(function () {
        $("#total_price").val(Math.abs(parseInt($(this).val()) * parseInt($("#selling_price").val())));
    })
    $("#btnAddInvoiceDetail").click(function () {
        addProductOnInvoice();
    })
    //make payment check amount info
    $("#given").keyup(function () {
        exchangeCalculator();
    });
    $("#paidamount").keyup(function () {
        if (!isNaN($(this).val())) {
            // makePayment();
            exchangeCalculator();//calculate amount to be given to client
        } else {//must be a number
            $("#regPayinfoResponse").html("<font color='red'>" + errormsg.amountnbr + "</font>");
            clearMsg("#regPayinfoResponse");
        }
    });
    $("#btnPayInvoice").click(function () {
        makePayment();
    })
    document.addEventListener("click", function () {
        if (event.target.classList.contains("invoice-buttons")) {
            invoiceButtons(event.target, JSON.parse(event.target.getAttribute("data-obj")));
        }
    })

    })
    ;

</script>