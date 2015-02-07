<?php
require("auth.php");
header("Content-type: text/html; charset=utf-8");
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$db = "/home/pi/NTH/db.json";
define("chunk",524288);
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $i = fopen("php://input","r");
  $h = fopen($db,"w");
  while(!feof($i))
  {
    fwrite($h,fread($i,chunk));
  }
  fclose($h);
  fclose($i);
  header("Connection: close");
  exit;
}
elseif(array_key_exists("db",$_GET))
{
  header("Content-type: text/plain");
  $h = fopen($db,"r");
  while(!feof($h))
  {
    echo fread($h,chunk);
  }
  fclose($h);
  exit;
}
?><!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin" />
  <script src="/rsc/res.js"></script>
  <script src="/phpjs?f=json_decode,json_encode,file_get_contents"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width" />
  <title>NTH list</title>
  <style>
  body
  {
    font-family: Ubuntu;
  }

  .center
  {
    transform: translateX(-50%) translateY(-50%);
    -webkit-transform: translateX(-50%) translateY(-50%);
    top: 50%;
    left: 50%;
  }
  #new
  {
    padding: 1.5%;
    background: white;
    z-index: 3;
    border-radius: 5px;
    position: fixed;
  }
  .inputs
  {
    margin: 0.5%;
  }
  #instruct
  {
    margin-top: 2%;
    margin-bottom: 2%;
  }
  able a:link {
    color: #666;
    font-weight: bold;
    text-decoration:none;
  }
  table a:visited {
    color: #999999;
    font-weight:bold;
    text-decoration:none;
  }
  table a:active,
  table a:hover {
    color: #bd5a35;
    text-decoration:underline;
  }
  table {
    font-family:Arial, Helvetica, sans-serif;
    color:#666;
    font-size:12px;
    text-shadow: 1px 1px 0px #fff;
    background:#eaebec;
    margin:20px;
    border:#ccc 1px solid;

    -moz-border-radius:3px;
    -webkit-border-radius:3px;
    border-radius:3px;

    -moz-box-shadow: 0 1px 2px #d1d1d1;
    -webkit-box-shadow: 0 1px 2px #d1d1d1;
    box-shadow: 0 1px 2px #d1d1d1;
  }
  table th {
    padding:21px 25px 22px 25px;
    border-top:1px solid #fafafa;
    border-bottom:1px solid #e0e0e0;

    background: #ededed;
    background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
    background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
  }
  table th:first-child {
    text-align: left;
    padding-left:20px;
  }
  table tr:first-child th:first-child {
    -moz-border-radius-topleft:3px;
    -webkit-border-top-left-radius:3px;
    border-top-left-radius:3px;
  }
  table tr:first-child th:last-child {
    -moz-border-radius-topright:3px;
    -webkit-border-top-right-radius:3px;
    border-top-right-radius:3px;
  }
  table tr {
    text-align: center;
    padding-left:20px;
  }
  table td:first-child {
    text-align: left;
    padding-left:20px;
    border-left: 0;
  }
  table td {
    padding:18px;
    border-top: 1px solid #ffffff;
    border-bottom:1px solid #e0e0e0;
    border-left: 1px solid #e0e0e0;

    background: #fafafa;
    background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
    background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
  }
  table tr.even td {
    background: #f6f6f6;
    background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
    background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
  }
  table tr:last-child td {
    border-bottom:0;
  }
  table tr:last-child td:first-child {
    -moz-border-radius-bottomleft:3px;
    -webkit-border-bottom-left-radius:3px;
    border-bottom-left-radius:3px;
  }
  table tr:last-child td:last-child {
    -moz-border-radius-bottomright:3px;
    -webkit-border-bottom-right-radius:3px;
    border-bottom-right-radius:3px;
  }
  table tr:hover td {
    background: #f2f2f2;
    background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
    background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);
  }
</style>
<script>
  var json = json_decode(file_get_contents(location.href+"?db")),
  nform,nfield,afield,pfield,sfield,sndbut,
  table = document.createElement("table");
  function mk()
  {
    shadow.show();
    nform.style.display = "";
  }
  function snd()
  {
    json.push({
      name: nfield.value,
      amount: afield.value,
      period: pfield.value,
      subj: sfield.value
    });
    var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.open("POST",location,true);
    xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        init();
        shadow.hide();
        nform.style.display = "none";
      }
    }
    xmlhttp.send(json_encode(json));
  }
  function init()
  {
    var pt = document.querySelector("table"),
    head = CE("tr"),
    hnam = CE("td"),
    hamo = CE("td"),
    hper = CE("td"),
    hsub = CE("td");
    if(pt != undefined){while(pt.firstChild) {pt.firstChild.remove();}}
    hnam.innerHTML = "Name";
    hamo.innerHTML = "Betrag";
    hper.innerHTML = "Periode";
    hsub.innerHTML = "Zweck";
    hnam.style.background = "rgb(250,170,250)";
    hamo.style.background = "rgb(250,170,250)";
    hper.style.background = "rgb(250,170,250)";
    hsub.style.background = "rgb(250,170,250)";
    hsub.style.background = "rgb(250,170,250)";
    head.appendChilds(hnam,hamo,hper,hsub);
    table.appendChild(head);
    for(var i = 0; i < json.length; i++)
    {
      var o = json[i],
      row = CE("tr"),
      nam = CE("td"),
      amo = CE("td"),
      per = CE("td"),
      sub = CE("td");
      nam.innerHTML = o.name;
      amo.innerHTML = o.amount+" &#8364;";
      per.innerHTML = o.period;
      sub.innerHTML = o.subj;
      row.appendChilds(nam,amo,per,sub);
      table.appendChild(row);
    }
    if(pt == undefined)
    {
      table.border = true;
      var btn = document.createElement("button");
      btn.innerHTML = "Neuer Eintrag";
      btn.addEventListener("click",mk);
      nform = document.getElementById("new");
      nfield = document.getElementById("name");
      afield = document.getElementById("amount");
      pfield = document.getElementById("period");
      sfield = document.getElementById("subject");
      sndbut = document.getElementById("snd");
      shadow.__construct();
      shadow.div.style.cursor = "pointer";
      shadow.div.addEventListener("click",function (e){shadow.hide();nform.style.display = "none"});
      sndbut.addEventListener("click",snd);
      document.body.appendChilds(table,CE("br"),CE("br"),btn);
    }
  }
  window.addEventListener("load",init);
</script>
</head>
<body>
  <div id="new" style="display: none;" class="center">
    <div class="inputs">
      <label for="name">Name: </label>
      <input style="margin-left: 17px;" required label="name" id="name" />
    </div>
    <div class="inputs">
      <label for="subject">Subject: </label>
      <input required style="margin-left: 6px;" label="subject" id="subject" />
    </div>
    <div class="inputs">
      <label for="amount">Amount: </label>
      <input style="margin-left: 1px;" required label="amount" id="amount" />
    </div>
    <div class="inputs">
      <label for="period">Period: </label>
      <input style="margin-left: 13px;" required label="period" id="period" />
    </div>
    <br />
    <button id="snd">Send</button>
  </div>
</body>
</html>
