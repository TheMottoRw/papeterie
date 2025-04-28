function ajax(url,getpars,typ,responseType,responseFunc){
    $.ajax({
        url:url,type:typ,data:getpars,dataType:responseType,success:responseFunc,cache:false,statuscode:{
            404:function(){
                alert('Response not found');
            }
        }
    });
}
function setLoaders(obj){
    if(document.getElementById(obj.elem)===null) return;
    switch(obj.elemtype){
        case'table':
            var thead=document.getElementById(obj.elem).getElementsByTagName('thead')[0];
            var tdLen=thead.getElementsByTagName('tr')[0].getElementsByTagName('th').length;//colspan for tbody
            var tbody=document.getElementById(obj.elem).getElementsByTagName('tbody')[0];
            tbody.innerHTML="<tr><td colspan="+tdLen+" style='font-size:14px;font-weight:bold'><center>"+obj.msg+"</center></td></tr>";
            break;
        case'container':
            document.getElementById(obj.elem).innerHTML=obj.msg;
            break;
    }
}
//AutoClear Msg
function clearMsg(elem){
    setTimeout(function(){
        $(elem).html("");
    },5000);
}