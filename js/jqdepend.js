
function createInvoice(){
    ajax("ajax/invoices",{"cate":"create","clientId":$("#clients").val(),"name":$("#name").val(),"phone":$("#phone").val()},"POST","text",function(res){
        if(res=="ok"){
            loadLastInvoice();
            loadInvoices();
            $("#name").val("");$("#phone").val("");
            $("#regInvoiceResponse").html("<font color='green'>Invoice Created Success</font>");
        }else{
            $("#regInvoiceResponse").html("<font color='red'>Failed to Create Invoice</font>");
        }
        clearMsg("#regInvoiceResponse");
    });
}
function loadInvoices(){
    var data={"cate":"load","sessid":$("#sessid").val(),"usercate":$("#usercate").val()},pgFeatures=getUrlFeatures();
    if(pgFeatures.page=='reports' && $("#invoicesDateFrom").val()!='' && $("#invoicesDateTo").val()!=''){
        data.start=$("#invoicesDateFrom").val();data.end=$("#invoicesDateTo").val();
    }
    setLoaders({elem:'invdone',elemtype:'table',msg:'Loading Data...'});
    ajax("ajax/invoices",data,"GET","json",function(res){
        if(!res.hasOwnProperty('message')){
            setLoadedInvoices(res.invoices);
        }else{//System Uses allready expired
            alert("System Uses Allready Expired at "+res.at+"\nyou are Being Redirected to Payment Page");
            window.location=res.url;
        }
    });
}
function loadLastInvoice(){
    ajax("ajax/invoices",{"cate":"loadlast","sessid":$("#sessid").val(),"usercate":$("#usercate").val()},"GET","text",function(res){
        $("#invid").val(res);
    });
}
function loadNextInvoice(){
    ajax("ajax/invoices",{"cate":"next","sessid":$("#sessid").val(),"usercate":$("#usercate").val()},"GET","text",function(res){
        $("#nextinv").html(res);
        $("#nextinv1").html(res);
    });
}
function loadInvoicesById(cate,id){
    ajax("ajax/invoices",{"cate":"loadbyid",sessid:$("#sessid").val(),usercate:$("#usercate").val(),"sessid":$("#sessid").val(),"usercate":$("#usercate").val(),"invoiceid":id},"GET","json",function(res){
        var res=res.invoices;
        switch(cate){
            case'payment':
                var cpid=res[0].clp_id==null?0:res[0].clp_id;
                $("#clpid").val(cpid);
                $("#cpinvoiceid").val(res[0].invoice_id);
                $("#cpclientid").val(res[0].invoice_clientid);
                $("#invoicesIdent").html(res[0].invoice_identifier);
                var total=res[0].total==null?0:res[0].total;
                var paid=res[0].clp_paid==null?0:res[0].clp_paid;
                var remain=res[0].clp_remain!=null?res[0].clp_remain:total;
                var cpid=res[0].clp_id==null?0:res[0].clp_id;
                $("#totalamount").val(total);$("#paidamount").val(paid);
                $("#remainamount").val(remain);$("#clpid").val(cpid);
                $("#remainhidamount").val(remain);$("#paidhidamount").val(paid);
            case'add':
                $("#invoiceidModalTitl").html("Select Products for Invoice "+res[0].invoice_identifier);
                break;
            case'view':
                loadInvoicesDetail(res[0].invoice_id);
                $("#invidt").append(res);
                break;
            case'delete':
                setDeleteInvoices(res);
                break;
            default:
        }
        $("#delinvid").val(id);
    });
}
function setLoadedInvoices(loadedinvoices){
    var invoices="",pgFeatures=getUrlFeatures();
    if(loadedinvoices.length!=0){
        for(var i=0;i<loadedinvoices.length;i++){
            var total=loadedinvoices[i].total==null?'0':loadedinvoices[i].total;
            invoices+="<tr>"
                +"<td>"+ (i+1)+"</td>"
                +"<td>"+(loadedinvoices[i].cl_names==null?'Unknown':loadedinvoices[i].cl_names)+"</td>"
                +"<td>"+loadedinvoices[i].invoice_identifier+"</td>"
                +"<td id='totalamount"+loadedinvoices[i].invoice_id+"'>"+total+" RWF</td>"
                +"<td>"+(loadedinvoices[i].clp_paid==null?'0':loadedinvoices[i].clp_paid)+" RWF</td>"
                +"<td>"+(loadedinvoices[i].clp_remain==null?total:loadedinvoices[i].clp_remain)+" RWF</td>"
                +"<td>"+ loadedinvoices[i].regdate.substring(0,16) +"</td>"
            if(pgFeatures.page!='reports'){
                invoices+="<td style='text-align:center;position:inherit;' class='invoicesmore'><li class='dropdown' style='list-style-type:none'><a href='#'id='dropBtn"+i+"' class='btn btn-info glyphicon glyphicon-full-screen dropdown-toggle' data-toggle='dropdown'>More <i class='fa fa-caret-down'></i></a>"
                    +" </a>"
                    +"<ul id='dropMenus"+i+"' class='dropdown-menu' role='menu' aria-labelledby='dropBtn"+i+"'>"
                    /*+"<td style='text-align:center;'><a href='#' id='addInvoicePayment' class='btn btn-info' data-toggle='modal' onclick='loadInvoicesProdParallelById(\"payment\","+loadedinvoices[i].invoice_id+")' data-target='#paymentInvoicesModal'><i class='fa fa-bitcoin'></i> Payment</a></td>"
                   +"<td style='text-align:center;'><a href='#' id='addInvoiceDt' class='btn btn-primary glyphicon glyphicon-plus'data-toggle='modal' onclick='loadInvoicesProdParallelById(\"add\","+loadedinvoices[i].invoice_id+")' data-target='#saveCreatedInvoiceDetailsModal'>Add</a></td>"
                   +"<td style='text-align:center;'><a href='#' id='loadInvoiceDt'class='btn btn-success glyphicon glyphicon-file'data-toggle='modal' onclick='loadInvoicesDetail("+loadedinvoices[i].invoice_id+")' data-target='#viewCreatedInvoiceDetailsModal' "+(loadedinvoices[i].total==null?'disabled':'')+">View</a></td>"
                   +"<td style='text-align:center;'><a href='ajax/invoices?cate=receipt&sessid="+$("#sessid").val()+"&usercate="+$("#usercate").val()+"&invoiceid="+loadedinvoices[i].invoice_id+" id='printReceipt' class='btn btn-warning glyphicon glyphicon-print'"+(loadedinvoices[i].total==null?'disabled':'')+">Receipt</a></td>"
                   +"<td style='text-align:center;'><a href='#' class='btn btn-danger glyphicon glyphicon-remove'data-toggle='modal' onclick='loadInvoicesById(\"delete\","+loadedinvoices[i].invoice_id+")' data-target='#delModal'>Delete</a>"
                    +" </a></td>"
                    */
                    +"<li><a href='#' id='addInvoicePayment' class='btn btn-info' data-toggle='modal' onclick='loadInvoicesProdParallelById(\"payment\","+loadedinvoices[i].invoice_id+")' data-target='#paymentInvoicesModal'"+(loadedinvoices[i].total==null?'disabled':'')+"><i class='fa fa-bitcoin'></i> Recover</a></li>"
                    +"<li><a href='#' id='viewInvoicePayment' class='btn btn-warning'  onclick='loadInvoicesClientPayment("+loadedinvoices[i].clp_id+")' "+(loadedinvoices[i].clp_id==null?'disabled':'')+"><i class='fa fa-eye'></i> Payment</a></li>"
                    +"<li><a href='#' id='addInvoiceDt' class='btn btn-primary glyphicon glyphicon-plus'data-toggle='modal' onclick='loadInvoicesProdParallelById(\"add\","+loadedinvoices[i].invoice_id+")' data-target='#saveCreatedInvoiceDetailsModal'"+(loadedinvoices[i].clp_paid!=null?'disabled':'')+">Add</a></li>"
                    +"<li><a href='#' id='loadInvoiceDt'class='btn btn-success glyphicon glyphicon-file'data-toggle='modal' onclick='loadInvoicesDetail("+loadedinvoices[i].invoice_id+")' data-target='#viewCreatedInvoiceDetailsModal' "+(loadedinvoices[i].total==null?'disabled':'')+">View</a></li>"
                    +"<li><a target='_blank' href='ajax/invoices?cate=receipt&sessid="+$("#sessid").val()+"&usercate="+$("#usercate").val()+"&invoiceid="+loadedinvoices[i].invoice_id+" id='printReceipt' class='btn btn-warning glyphicon glyphicon-print'"+(loadedinvoices[i].total==null?'disabled':'')+">Receipt</a></li>"
                    +"<li><a href='#' class='btn btn-danger glyphicon glyphicon-remove' data-toggle='modal' onclick='loadInvoicesById(\"delete\","+loadedinvoices[i].invoice_id+")' data-target='#delModal'>Delete</a>"
                    +" </a></li>"
                    +"</div></ul></li></td>"
            }
            +"</tr>";
        }
    }else{
        invoices+="<tr>"
            +" <td colspan='8'><center>No Invoices Found</center></td></tr>"

    }
    $("#invoicesdone").html("");
    $("#invoicesdone").append(invoices);
    jslive.pagingTable({id:'#invdone',shows:25,active:0});
}
function setDeleteInvoices(data){
    $("#delinvid").val(data[0].invoice_id);
    $("#invoicesDelIdent").html(data[0].invoice_identifier+"?");
}
function loadInvoicesProdParallelById(cate,invoiceid){

    loadInvoicesById(cate,invoiceid);//loading invoices
    loadNonZeroStockValue();
    $("#invid").val(invoiceid);
}
//About Invoices Clients Payment
function loadInvoicesClientPayment(id){
    setLoaders({elem:'viewinvpayment',elemtype:'table',msg:'Loading Data...'});
    var data={"cate":"loadcredits","sessid":$("#sessid").val(),"usercate":$("#usercate").val(),"clpid":id}
    ajax("ajax/clientpayment",data,"GET","json",function(res){
        setLoadedInvoicesClientPayment("view",res.clientcpay);
    });
    //hiding invoice table and show payment table
    $("#invdone").hide()
    $("#viewinvpayment").show();
}
function loadInvoicesClientPaymentById(cate,id){
    ajax("ajax/clientpayment",{"cate":"loadcreditsbyid","sessid":$("#sessid").val(),"usercate":$("#usercate").val(),"clcpid":id},"GET","json",function(res){
        setLoadedInvoicesClientPayment(cate,res.clientcpay);
    });
}
function setLoadedInvoicesClientPayment(cate,loadedinvoicepayment){
    var invoicecreditpayment="";
    if(cate=='view'){
        if (loadedinvoicepayment.length!=0) {
            $("#invidtcp").html(loadedinvoicepayment[0].invoice_identifier);
            for(var i=0;i<loadedinvoicepayment.length;i++){
                invoicecreditpayment+="<tr>"
                    +"<td>"+ (i+1) +"</td>"
                    +"<td>"+ loadedinvoicepayment[i].cl_names +"</td>"
                    +"<td>"+ loadedinvoicepayment[i].cl_phone +"</td>"
                    +"<td>"+ loadedinvoicepayment[i].invoice_identifier +"</td>"
                    +"<td>"+ (loadedinvoicepayment[i].clcp_paid!=null?(parseInt(loadedinvoicepayment[i].clcp_paid)+parseInt(loadedinvoicepayment[i].clcp_remain)):(parseInt(loadedinvoicepayment[i].clp_paid)+parseInt(loadedinvoicepayment[i].clp_remain))) +" RWF</td>"
                    +"<td>"+ loadedinvoicepayment[i].clcp_paid+" RWF</td>"
                    +"<td>"+ loadedinvoicepayment[i].clcp_remain +" RWF</td>"
                    +"<td>"+ loadedinvoicepayment[i].regdate.substring(0,16)+"</td>"
                    +"<td style='text-align:center;' class='invpaymore'>"+(loadedinvoicepayment.length-1!=i?'Readonly':"<a href='#' onclick='loadInvoicesClientPaymentById(\"edit\","+loadedinvoicepayment[i].clcp_id+")' class='btn btn-warning edit glyphicon glyphicon-pencil' data-toggle='modal' data-target='#updpaymentInvoicesModal'>Edit</a>")
                    +"&nbsp;&nbsp;"+(loadedinvoicepayment.length-1!=i?'Readonly':"<a href='#' onclick='loadInvoicesClientPaymentById(\"delete\","+loadedinvoicepayment[i].clcp_id+")' class='btn btn-danger glyphicon glyphicon-remove' data-toggle='modal' data-target='#delInvCreditPayModal' >Delete</a>")
                    +"</a></td>"
                    +"</tr>";
            }
        }else{
            invoicecreditpayment+="<tr>"
                +" <td colspan='9'><center>No Invoices Client Credit Payment Found</center></td></tr>"

        }
        $("#loadedinvpayinfo").html(invoicecreditpayment);
        $("#viewinvoicecreditpayment").show();
        $("#viewinvoicepayment").hide();
        jslive.pagingTable({id:'#viewinvpayment',shows:25,active:0});
    }//end view
    else if(cate=='edit'){
        setEditInvoicesClientPayment(loadedinvoicepayment);
    }else if(cate=='delete'){
        setDeleteInvoicesClientPayment(loadedinvoicepayment);
    }
}
function setEditInvoicesClientPayment(data){
    $("#clcpid").val(data[0].clcp_id);
    $("#updtotalamount").val(parseInt(data[0].clp_remain)+parseInt(data[0].clp_paid));
    $("#updinvoicesIdent").html(data[0].cl_names+" ["+data[0].invoice_identifier+"]");
    $("#updremainamount").val(data[0].clcp_remain);
    $("#updremainhidamount").val(data[0].clcp_remain);
    $("#updpaidhidamount").val(data[0].clcp_paid);
    $("#updpaidamount").val(data[0].clcp_paid);
}
function setDeleteInvoicesClientPayment(data){
    $("#delinvclcpid").val(data[0].clcp_id);
}

function updateInvoicesClientPayment(){
    setLoaders({elem:'updPayinfoResponse',elemtype:'container',msg:'Updating Data...'});
    ajax("ajax/clientpayment",{cate:"updatecredit",sessid:$("#sessid").val(),usercate:$("#usercate").val(),clcpid:$("#clcpid").val(),paid:$("#updpaidamount").val(),remain:$("#updremainamount").val()},"POST","text",function(res){
        if(res=='ok'){
            $("#updPayinfoResponse").html("<font color='green'>Update Invoices Client Repayment  Done Succesfull</font>");
            loadInvoices();
            setTimeout(function(){
                document.getElementById("closeModRepyStcp").click();
                document.getElementById("btnbackinv").click();},5000);
        }else if(res=='fail'){
            $("#updPayinfoResponse").html("<font color='red'>Failed to Update Invoices Client Repayment</font>");
        }
    });
    clearMsg("#updPayinfoResponse");
}
function deleteInvoicesClientPayment(){
    ajax("ajax/clientpayment",{cate:"deletecredit",sessid:$("#sessid").val(),usercate:$("#usercate").val(),id:$("#delinvclcpid").val(),reason:$("#deleteinvclcpreason").val()},"POST","text",function(res){
        if(res=='ok'){
            $("#deleteinvclcpreason").val("");
            $("#delClcpResponse").html("<font color='green'>Delete Invoices Client Repayment  Done Succesfull</font>");
            loadInvoices();
        }else if(res=='fail'){
            $("#delClcpResponse").html("<font color='red'>Failed to Delete Invoices Client Repayment</font>");
        }
    });
    clearMsg("#delClcpResponse");
}
//End Invoices Client Payment