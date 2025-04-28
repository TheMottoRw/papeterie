/*
Author:MANZI NERETSE Roger
Phone:250784634118
E-mail:mnzroger@gmail.com
Skype:Manzi552
Linkedin:MANZI ROGER ASUA
Twitter:@ManziRAsua
Website:www.bfg.rw

This Plugins is Allowed tone Priviledged and Licensed to Our System
*/
var jslive={};
jslive.validate=function (obj) {
	var feed=obj.type=='number'?number(obj.data):
	obj.type=='string'?string(obj.data):
	obj.type=='except'?exception(obj.data,obj.except):
	obj.type==='ID'?rwandanID(obj.data):
	obj.type=='phone'?obj.category=='rwandan'?phone(obj.data,obj.category):
											obj.category=='international'?phone(obj.data,obj.category):
											"Category Not Available "+obj.category:"Invalid type "+obj.type;
return feed;
}
//optional for validation
var number=function(str) {//creating validator for numbers
var pattern=/^[0-9.]+$/;
			return pattern.test(str);
	}//end of number validator
	
	var string=function(str) {//creating string validator
		var pattern=/^[^0-9]+$/;
		return pattern.test(str);
	}//end of string validator
	
		var exception=function(str,exceptionpars) {//creating combined validator
		var pattern=new RegExp([exceptionpars]);
		return !pattern.test(str);
}//end exception validation

var phone=function(str,category) {//phone validator
	function rwandan(str) {
		var patt12=/250 7[2,3,8][0-9]{1} [0-9]{3} [0-9]{3}/;
		var patt10=/07[2,3,8] [0-9]{1} [0-9]{3} [0-9]{3}/;
		str=str.length==10?str.substr(0,3)+" "+str.substr(3,1)+" "+str.substr(4,3)+" "+str.substr(7,3):
		str.length==12?str.substr(0,3)+" "+str.substr(3,3)+" "+str.substr(6,3)+" "+str.substr(9,3):str;
		var feed=str.length==13?patt10.test(str):
		str.length==15?patt12.test(str):"invalid Phone number";
		return feed;
}
function international(str) {
		var pattern=/[0-9]{1,3}-[0-9]{3}-[0-9]{3}-[0-9]{3}/;
		return (str.length<=12 && str.length>=10)?pattern.test(str.substr(0,(str.length-9))+"-"+str.substr((str.length-9),3)
			+"-"+str.substr((str.length-6),3)+"-"+str.substr((str.length-3),3))+"  12":(str.length<=15 && str.length>12)?pattern.test(str)+"  15":"Invalid Phone=>"+str+"=>"+str.length;
			
		//return (str.length<=12 && str.length>=10)?pattern.test(str):"International Invalid Phone=>"+str;
}
return category=='international'?international(str):rwandan(str)+"=>"+str;
}//end phone validator

var rwandanID=function(str) {//rwandan ID validator
		var pattern=/1 [0-9]{4} [7,8] [0-9]{7} [0-9]{1} [0-9]{2}/;
		str=str.length==16?str.substr(0,1)+" "+str.substr(1,4)+" "+str.substr(5,1)+" "+str.substr(6,7)
		+" "+str.substr(13,1)+" "+str.substr(14,2):str;
		var feed=str.length==21?pattern.test(str):" invalid ID number";
		return feed;
}

//AJax and jsonp
var xmlhttp;
jslive.ajax=function(obj)
{
	xmlhttp=new XMLHttpRequest();
	if (obj.hasOwnProperty("url")) {
	if (obj.hasOwnProperty("method")==true){
	if (obj.method=='GET') {
		obj.hasOwnProperty("params")==true?xmlhttp.open(obj.method,obj.url+"?"+obj.params,true):xmlhttp.open(obj.method,obj.url,true);
xmlhttp.send();
}
if (obj.method=='POST') {
	xmlhttp.open(obj.method,obj.url,true)
xmlhttp.send(obj.params)	
	}
}//end has property method

xmlhttp.onreadystatechange=function () {
	if (this.readyState==4 && this.status==200) {
		obj.hasOwnProperty("responseType")==true?
			obj.hasOwnProperty("responseFunc")==true?obj.responseType=='text'?obj.responseFunc(this.responseText):
obj.responseType=='json'?obj.responseFunc(JSON.parse(this.responseText)):obj.responseType=='xml'?obj.responseFunc(this.responseXML):console.log("Invalid response Type")
:console.log("Response function not defined"):console.log("Response Type not defined");
}//end if status==200
}//if readystate change
}//end url checking
else {
	console.log("Requesting URL Not defined")
}
}
//forms and events
jslive.tickAll=function (elemclass,category) {
	category=='tick'?chkAll(elemclass):category=='untick'?unchkAll(elemclass):console.log("invalid category ->"+category);
	};
	var stats=false;
function chkAll(elemclass) {
	var feed;
	if (document.getElementsByClassName(elemclass).length!=0) {
if (stats==false) {
	for (i=0;i<document.getElementsByClassName(elemclass).length;i++) {
		document.getElementsByClassName(elemclass)[i].checked=true;
}
stats=true
}else {
	
	for (i=0;i<document.getElementsByClassName(elemclass).length;i++) {
		document.getElementsByClassName(elemclass)[i].checked=false;

}
stats=false;	
}
}else {
	console.log("Element not found");
}
return feed;
}
function unchkAll(elemclass) {
		document.getElementsByClassName(elemclass)[0].checked=false;

stats=false;
}


//reading Text
jslive.readText=function (elemId) {
	var msg = new SpeechSynthesisUtterance(document.querySelector(elemId).value);
	 window.speechSynthesis.speak(msg);
}
//end forms and events

//Document,Browser and OS
jslive.getURL=function () {
	console.log(document.URL);
	return document.URL;
}
jslive.getDomain=function () {
	console.log(document.domain);
	return document.domain;
}
jslive.getBrowserName=function () {
	console.log(navigator.appName);
	return navigator.appName;
}
jslive.getBrowserVersion=function () {
	console.log(navigator.appVersion);
	return navigator.appVersion;
}
jslive.getBrowserLanguage=function () {
	console.log(navigator.language);
	return navigator.language;
}
jslive.getOS=function () {
	console.log(navigator.platform);
	return navigator.platform;
}
jslive.getPlugins=function () {
	console.log(navigator.plugins);
	return navigator.plugins;
}
jslive.getPath=function () {
console.log(location.pathname);
return location.pathname;
}
jslive.newWindow=function (obj){
	var height=300,width=600,title=this.getURL(),left=300,top=200;
	obj.hasOwnProperty("height")?height=obj.height:'';
	obj.hasOwnProperty("width")?width=obj.width:'';
	obj.hasOwnProperty("title")?title=obj.title:'';
	obj.hasOwnProperty("left")?left=obj.left:'';
	obj.hasOwnProperty("top")?top=obj.top:'';
obj.hasOwnProperty("url")==true?
window.open(obj.url,"ASUA NEW","height="+height+",width="+width+",title="+title+",left="+left+",top="+top):console.log("No URL Passed");
} 
//Key Boards Keys Value

jslive.shortcuts=function (trick,responseFunc) {
		var tricks="",prevKey="";
	document.addEventListener("keydown",function (event) {
	
if (event.key!=prevKey) {
	if (tricks=="") {
			tricks+=event.key;
		}else {
	tricks+="+"+event.key;		
}
	//console.log("This "+tricks.replace("Control","Ctrl"));
	prevKey=event.key;
}


		if (trick===(tricks.replace("Control","Ctrl"))) {
												console.log("Nice Done");
											responseFunc();
											tricks="";prevKey="";//resetting
	}else {
setTimeout(function () {	tricks="";prevKey="";
	},3000);
}
});
}
//events
jslive.controls=function(identifier) {
	 var ident=document.querySelectorAll(identifier);	
return ident;
}
/*
Object.prototype.setEvent=function (e,responseFunc){
	for (var i=0;i<this.length;i++) {
		this[i].addEventListener(e,function () {
			responseFunc();
		});
}
		};
Object.prototype.setText=function(data){//setting text to element
	for (var i=0;i<this.length;i++) {
		this[i].innerHTML=data;
		};
}
Object.prototype.getText=function(data){
	var data=new Array();
for (var i=0;i<this.length;i++) {
		data[i]=this[i].innerHTML;
		};
		return data;
}
Object.prototype.setValue=function(data){//setting text to element
	for (var i=0;i<this.length;i++) {
		this[i].value=data;
		};
}
Object.prototype.getValue=function(data){//setting text to element
	var data=new Array(),d=0;
	for (var i=0;i<this.length;i++) {
		if(this[i].getAttribute("type")!='checkbox'){
		data[d]=this[i].value;
		d++;
	}else{
		if(this[i].checked==true){
		data[d]=this[i].value;
		d++;
		}//end if to check if checkbox checked
	}//end else
		};
		return data;
}
Object.prototype.setHolder=function(data){//setting text to element
	for (var i=0;i<this.length;i++) {
		this[i].placeholder=data;
		};
}
Object.prototype.setAttribute=function(attribute,val){
	for (var i=0;i<this.length;i++) {
		this[i].setAttribute(attribute,val);
		};
}
Object.prototype.getAttribute=function(attribute){
	for (var i=0;i<this.length;i++) {
		this[i].getAttribute(attribute);
		};
}
Object.prototype.setSelectedIndex=function(index){
	var options=this[0].getElementsByTagName("option");
for (var i=0;i<options.length;i++) {
	if(index==(i+1)){
		for (var a=0;a<options.length;a++) {
		options[a].removeAttribute("selected");
	}
	options[i].setAttribute("selected","selected");
		}
		};
}
Object.prototype.setSelectedItem=function(item){
	var options=this[0].getElementsByTagName("option");
for (var i=0;i<options.length;i++) {
		if(options[i].innerHTML==item){
		for (var a=0;a<options.length;a++) {
		options[a].removeAttribute("selected");
	}
	options[i].setAttribute("selected","selected");
		}
		};
}
*/
//Searchable and Paging Table
//paging for dataTables
jslive.pagingTable=function(obj){
	var thead=document.querySelector(obj.id).getElementsByTagName('thead');
	var tbody=document.querySelector(obj.id).getElementsByTagName("tbody");
	var tr=tbody[0].getElementsByTagName("tr");
	var theadtr=thead[0].getElementsByTagName("tr");
	var totalRow=tr.length;
	//alert(tr[4].getElementsByTagName("th")[0].innerHTML);
	var pages=Math.ceil(totalRow/parseInt(obj.shows));
	var tblId=obj.id;
	var activePage=obj.active;
	var showsRow=obj.shows;
	var start=activePage*parseInt(obj.shows);
	var end=start+parseInt(obj.shows)-1;
	var shows={from:start,to:end}
for(var i=0;i<totalRow;i++){
if(shows.from<=i && shows.to>=i){
tr[i].removeAttribute('style');//hidding rows
}else{
tr[i].style.display='none';
}//end else of if
//thead[0].appendChild("<tr><th colspan='"+theadtr.length+"'>Hey All</th></tr>");
}//end for loop
//Create Header Row for Select & Search Boxes
var newTr=document.createElement("tr");
newTr.style.border='0px solid black';
var newTh=document.createElement("th");
newTh.setAttribute('colspan',thead[0].getElementsByTagName('th').length);
if(thead[0].getElementsByTagName('div').length==0){//create div for search and show rows
//Setup Heading dataShows for Users Number of Results to Show
var div=document.createElement("div");
var span=document.createElement("span");
var span1=document.createElement("span");
var select=document.createElement("select");
select.setAttribute('class','shows form-control pull-right');
select.style='width:10%;padding:3px;';
select.addEventListener('change',function(){
	jslive.pagingChangeTable({id:obj.id,shows:this.value,active:0,val:thead[0].getElementsByClassName('tblSearch')[0].value});
});
var options="<option>5</option><option>15</option><option selected>25</option><option>50</option><option>100</option><option>150</option><option>250</option><option>500</option>";
var search=document.createElement("input");
search.setAttribute("type","text");
search.setAttribute("id","tblSearch");
search.setAttribute("placeholder","Enter Keyword to Search");
search.setAttribute("class","form-control tblSearch pull-left");
search.style='width:90%;padding:10px;font-weight:normal;font-size:15px;padding:10px;';
search.addEventListener("keyup",function(){
jslive.searchTable({id:obj.id,val:this.value,shows:obj.shows,active:obj.active});//calling searchtable
});
select.innerHTML=options;
span.appendChild(select);
span1.appendChild(search);
div.appendChild(span);
div.appendChild(span1);
newTh.appendChild(div)
newTr.appendChild(newTh);
thead[0].appendChild(newTr);
}
//Adding Paging to data
//Loops to show paging Button
var btnPage=new Array();
var objData={id:obj.id,shows:obj.shows,active:i};
for(var i=0;i<(pages);i++){
btnPage[i]=document.createElement("button");
btnPage[i].setAttribute("type","button");
btnPage[i].setAttribute("class","btn btn-primary btn-sm btnPagers");
btnPage[i].setAttribute("data",obj.id+"-"+obj.shows+"-"+i);
btnPage[i].style='margin:3px;';

btnPage[i].addEventListener('click',function(){
	var btns=document.getElementsByClassName('btnPagers');
	for(var x=0;x<btns.length;x++){
btns[x].setAttribute("class","btn btn-primary btn-sm btnPagers");
}
this.removeAttribute('class');
this.setAttribute('class','btn btn-success btn-sm btnPagers');
	var data=this.getAttribute("data").split("-");
jslive.pagingTable({id:data[0],shows:data[1],active:data[2],val:data[3]});
});
btnPage[i].innerHTML=(i+1);
}
//Append it to last Row of Table
if(tbody[0].getElementsByClassName("btnPaging").length==0){
var tr1=document.createElement('tr');
var th1=document.createElement('th');
tr1.setAttribute("class","btnPaging");
th1.setAttribute('colspan',thead[0].getElementsByTagName('th').length);
th1.style='text-align:center';
for(var x=0;x<btnPage.length;x++){
th1.appendChild(btnPage[x]);
}
tr1.appendChild(th1);
tbody[0].appendChild(tr1);
}else{
tr[(tr.length-1)].removeAttribute('style');
}
//shownig needed rows
}//end paging
jslive.pagingChangeTable=function(obj){//when comboBoxChanged
	var thead=document.querySelector(obj.id).getElementsByTagName("thead")[0];
	obj.shows=thead.getElementsByClassName('shows')[0].value;//change values to show
	var tbody=document.querySelector(obj.id).getElementsByTagName("tbody");
	var tr=tbody[0].getElementsByTagName("tr");
	var totalRow=tr.length;
	var pages=Math.ceil(totalRow/parseInt(obj.shows));
	var activePage=obj.active;
	var showsRow=obj.shows;
	var start=activePage*parseInt(obj.shows);
	var end=start+parseInt(obj.shows)-1;
	var shows={from:start,to:end}

	if(obj.val==''){
//Hide &Show Rows
for(var i=0;i<totalRow;i++){
if(shows.from<=i && shows.to>=i){
tr[i].removeAttribute('style');//hidding rows
}else{
tr[i].style.display='none';
}//end else of if
}//end for loop
//Adding Paging to data
//Loops to show paging Button
var btnPage=new Array();
for(var i=0;i<(pages);i++){
btnPage[i]=document.createElement("button");
btnPage[i].setAttribute("type","button");
btnPage[i].setAttribute("class","btn btn-primary btn-sm btnPagers");
btnPage[i].setAttribute("data",obj.id+"-"+obj.shows+"-"+i);
btnPage[i].style='margin:3px;';
btnPage[i].addEventListener('click',function(){
	var btns=document.getElementsByClassName('btnPagers');
	for(var x=0;x<btns.length;x++){
btns[x].setAttribute("class","btn btn-primary btn-sm btnPagers");
}
this.removeAttribute('class');
this.setAttribute('class','btn btn-success btn-sm btnPagers');
	var data=this.getAttribute("data").split("-");
jslive.pagingTable({id:data[0],shows:data[1],active:data[2],val:data[3]});
});
btnPage[i].innerHTML=(i+1);
};
//Append it to last Row of Table
var tr1=document.createElement('tr');
var th1=document.createElement('th');
th1.style='text-align:center';
if(tbody[0].getElementsByClassName("btnPaging").length==0){
tr1.setAttribute("class","btnPaging");
th1.setAttribute('colspan',thead.getElementsByTagName('th').length);
for(var x=0;x<btnPage.length;x++){
th1.appendChild(btnPage[x]);
}
tr1.appendChild(th1);
tbody[0].appendChild(tr1);
}else{//Updating Buttons by removing Childs
	for(var i=0;i<tbody[0].getElementsByClassName("btnPaging").length;i++){
tbody[0].removeChild(tbody[0].getElementsByClassName("btnPaging")[i]);
	}
	//Adding New Updated Button
	tr1.setAttribute("class","btnPaging");
th1.setAttribute('colspan',tr[0].getElementsByTagName('td').length);
for(var x=0;x<btnPage.length;x++){
th1.appendChild(btnPage[x]);
}
tr1.appendChild(th1);
tbody[0].appendChild(tr1);

tr[(tr.length-1)].removeAttribute('style');
}
}//end check if search keyword exist
else{//it exist redirect to searchtable
jslive.searchTable(obj);
}
}//end changeTable functions
jslive.searchTable=function(obj){
	var thead=document.querySelector(obj.id).getElementsByTagName("thead")[0];
	obj.shows=thead.getElementsByClassName('shows')[0].value;//change values to show
	var counter=0;
var tbody=document.querySelector(obj.id).getElementsByTagName("tbody");
var tr=tbody[0].getElementsByTagName("tr");
if(obj.val!=''){
for(var i=0;i<tr.length;i++){
	var td=tr[i].getElementsByTagName("td");
	for(var a=0;a<td.length;a++){//compares values in tds
if(td[a].innerHTML.toLowerCase().indexOf(obj.val.toLowerCase())!='-1'){
	//setting color for searched item
	counter++;
tr[i].removeAttribute('style');
break;
}else{
	tr[i].style.display='none';
}
}//end for loop for cols[td]
}//end for loop for rows[tr]
//Hide &Show Rows
document.getElementsByClassName('btnPaging')[0].style.display='none';
}//end of if to check if it is empty
else{//display all row
jslive.pagingChangeTable({id:obj.id,active:obj.active,shows:obj.shows,val:''});
}//end else
}//end jslive.to search in table
//end Document
//Translating data
jslive.translate=function(lang){
		//alert(lang);
	jslive.ajax({
		url:'languages/language.hd',responseType:'json',method:'GET',
		responseFunc:function(res){
			jslive.translator(res[lang]);
		}
	})
}
jslive.translator=function(data){
for(key in data){
	if(typeof data[key]!='function' && typeof data[key]!='object'){
	//loops in all element to change value
	if(document.getElementsByClassName(key).length>0){
	for(var i=0;i<document.getElementsByClassName(key).length;i++){
var keys=document.getElementsByClassName(key)[i];
if(keys.hasAttribute("type")==true && (keys.type=='button' || keys.type=='submit' || keys.type=='reset')){
	keys.value=data[key];
}else if(keys.hasAttribute('placeholder')==true){
	keys.placeholder=data[key];
}else{
	keys.innerHTML=data[key];
	}
}
}
	if(document.getElementsByName(key).length>0){
	for(var i=0;i<document.getElementsByName(key).length;i++){
var keys=document.getElementsByName(key)[i];
if(keys.hasAttribute("type")==true && (keys.type=='submit' || keys.type=='reset')){
	keys.value=data[key];
}else if(keys.hasAttribute('placeholder')==true){
	keys.placeholder=data[key];
}else{
	keys.innerHTML=data[key];
	}
}
}
if(document.getElementById(key)!=null){
	var keys=document.getElementById(key);
if(keys.hasAttribute("type")==true && (keys.type=='submit' || keys.type=='reset')){
	keys.value=data[key];
}else if(keys.hasAttribute('placeholder')==true){
	keys.placeholder=data[key];
}else{
	keys.innerHTML=data[key];
	}
}
if(document.getElementsByTagName(key).length>0){
	for(var i=0;i<document.getElementsByTagName(key).length;i++){
var keys=document.getElementsByTagName(key)[i];
if(keys.hasAttribute("type")==true && (keys.type=='submit' || keys.type=='reset')){
	keys.value=data[key];
}else if(keys.hasAttribute('placeholder')==true){
	keys.placeholder=data[key];
}else{
	keys.innerHTML=data[key];
	}
}
}
}
}//end for loop to loop in keys
}//end of functions
//Dates Functions
jslive.timeBetween=function(obj){
	var time={};//,hours:null,min:null,seconds:null};
var diff=new Date(obj.to).getTime();
var diff1=obj.from.getTime();
if(obj.rule=='future' && (diff1<diff)){
var diffrence=(diff-diff1);
}else if(obj.rule=='past' && (diff1>diff)){
var diffrence=(diff1-diff);
}
time.rule=obj.rule;
time.from=obj.from;
time.to=obj.to;
if(diffrence>0){
if(diffrence>(1000*60*60*24*30)){//Months
time.months=Math.floor(diffrence/(1000*60*60*24*30));
time.days=Math.floor((diffrence-(time.months*1000*60*60*24*30))/(1000*60*60*24));
time.hours=Math.floor((diffrence-(time.months*1000*60*60*24*30)-(time.days*1000*60*60*24))/(1000*60*60));
time.min=Math.floor((diffrence-(time.months*1000*60*60*24*30)-(time.days*1000*60*60*24)-(time.hours*1000*60*60))/(1000*60));
time.seconds=Math.floor(((diffrence-(time.months*1000*60*60*24*30)-(time.days*1000*60*60*24)-(time.hours*1000*60*60))-(time.min*1000*60))/(1000));
}else if(diffrence>(1000*60*60*24)){//days
time.months=0;
time.days=Math.floor(diffrence/(1000*60*60*24));
time.hours=Math.floor((diffrence-(time.days*1000*60*60*24))/(1000*60*60));
time.min=Math.floor((diffrence-(time.days*1000*60*60*24)-(time.hours*1000*60*60))/(1000*60));
time.seconds=Math.floor(((diffrence-(time.days*1000*60*60*24)-(time.hours*1000*60*60))-(time.min*1000*60))/(1000));
}else if(diffrence>(1000*60*60)){//hours
time.months=0;
time.days=0;
time.hours=Math.floor(diffrence/(1000*60*60));
time.min=Math.floor((diffrence-(time.hours*1000*60*60))/(1000*60));
time.seconds=Math.floor(((diffrence-(time.hours*1000*60*60))-(time.min*1000*60))/(1000));
}else if(diffrence>(1000*60)){//minutes
time.months=0;
time.days=0;
time.hours=0;
time.min=Math.floor(diffrence/(1000*60));
time.seconds=Math.floor((diffrence-(time.min*1000*60))/(1000));
}else if(diffrence>1000){//seconds
time.months=0;
time.days=0;
time.hours=0;
time.min=0;
time.seconds=Math.floor(diffrence/(1000));
}
time.status=true;
}else{
time.status=false;
}
//getting Message required to obtain data
var fd=jslive.setUpMessage(time);
return fd;
}
//proving function toreturn Message required
jslive.setUpMessage=function(obj){
	var message=null;
	if(obj.status==true){
if(obj.months!=0){
message=obj.months+" M "+obj.days+" D "+obj.hours+" H "+obj.min+" Min "+obj.seconds+" S";
}else if(obj.days!=0){
message=obj.days+" D "+obj.hours+" H "+obj.min+" Min "+obj.seconds+" S";
}else if(obj.hours!=0){
message=obj.hours+" H "+obj.min+" Min "+obj.seconds+" S";
}else if(obj.min!=0){
message=obj.min+" Min "+obj.seconds+" S";
}else if(obj.seconds!=0){
message=obj.seconds+" S";
}
}else{
	console.log("Mismatch of Rule and Date Passed,From "+obj.from+" To "+obj.to +" Condition "+obj.rule);
	message="Invalid";	
}
//alert(message);
return message;
}
//calling function to obtain message based on time
jslive.differenceDate=function(obj){
	var feed="";
	var msg='';
	if(obj.hasOwnProperty("from")==true){
	feed=jslive.timeBetween({from:new Date(obj.from),to:obj.to,rule:obj.rule});
		msg=feed!='Invalid'?(obj.rule=='past'?'-':''):'';
	setTimeout(function(){
	document.querySelector(obj.elem).innerHTML=msg+""+feed;},0);
	}else{
		setInterval(function(){
	feed=jslive.timeBetween({from:new Date(),to:obj.to,rule:obj.rule});
		msg=feed!='Invalid'?(obj.rule=='past'?'-':''):'';
		setTimeout(function(){
	document.querySelector(obj.elem).innerHTML=msg+""+feed;},0);
},1000);
	}
}
jslive.upload=function(obj){
	var elem=document.querySelector(obj.elem);
	var feed=false;
	for (var i=0;i<elem.files.length;i++) {
		var data=new FormData();
		data.append("fl",elem.files[i]);
		data.append("cate","upload");
		data.append("name",document.querySelector("#pathSelected").value);
			jslive.ajax({
				url:obj.url,method:'POST',params:data,responseType:obj.responseType,responseFunc:function(res){
					return res;
				}
			});
		}
}
//tree object


//end tree
var obj={"name":"MANZI Roger Asua Software Developer"};
//alert(typeof jslive+"=>"+obj.hasOwnProperty("name"));
/*
Object.prototype.getName=function () {
	//alert(obj.name);
}
*/
jslive.getRut=function(){
		console.log("\nWassup Rutger");
		};
	jslive.getAsua=function(){
		console.log("\nWassup Asua")
		};
