<?php
$authfile = "/home/pi/NTH/secret";
if(!file_exists($authfile))
{
  file_put_contents($authfile,json_encode(Array()));
}
$authjson = o2a(json_decode(file_get_contents($authfile)));
$ua = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];
$authsuccess = idk($authjson,$ip,$ua);
if(!$authsuccess)
{
  if(!isset($_POST['pwd']))
  {
    pwdprompt(false);
  }
  if(md5($_POST['pwd']) == "a3cd9fe084df26198fe7e6859cafc744")
  {
    if(!isset($authjson[$ip]))
    {$authjson[$ip] = Array();}
    $authjson[$ip][] = $ua;
    file_put_contents($authfile,json_encode($authjson));
    echo '<!DOCTYPE html>
    <html>
    <head>
    <script>location.href="' . $_SERVER['PHP_SELF'] . '";</script>
    </head>
    <body>
    </body>
    </html>';
    exit;
  }
  else
  {
    //echo "<!-- " . $_POST['pwd'] . " -->";
    pwdprompt(true);
  }
}
function idk($authjson,$ip,$ua)
{
  foreach($authjson[$ip] as $comp)
  {
    if($comp == $ua)
    {
      return true;
    }
  }
  return false;
}
function pwdprompt($wrongpwd = false)
{
  $a = "";
  if($wrongpwd)
  {
    $a = "<h2>Wrong password, please try again</h2>";
  }
  die('<!DOCTYPE html>
  <html>
  <head>
  <script src="/phpjs?f=md5,utf8_encode"></script>
    <script>
    window.onload=function (){document.getElementById("password").onkeyup=function (){document.getElementById("md5").value=md5(document.getElementById("password").value);}
    document.getElementsByTagName("form")[0].onsubmit=function () {document.getElementById("password").value="";};}
    </script>
  </head>
  <body style="font-family:helvetica;">
    <h1>Please enter the password for ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</h1>
    ' . $a  . '
    <form method="post">
      <input id="password" type="password" />
      <input type="hidden" name="pwd" id="md5" />
      <input type="submit" value="Login" />
    </form>
  </body>
  </html>
  ');
}
function o2a($d) {if (is_object($d)) {$d = get_object_vars($d);}if (is_array($d)) {return array_map(__FUNCTION__, $d);}else {return $d;}}
?>
