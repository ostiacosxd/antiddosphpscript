<?php
$cookie = "AntiDDosByCuidabrutos";
$othercookie = 'AnotherAntiDDos';


if($cookie && $othercookie > 0) $iptime = 20;
else $iptime = 10;


$ippenalty = 60;


if($cookie && $othercookie > 0)$ipmaxvisit = 30; 
else $ipmaxvisit = 20;
function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
 
    return $ip;
}
function getOS() {
    if (PHP_OS == 'WINNT'){
		$os = "Windows";
        return $os;
		
    }

    if (PHP_OS == 'Linux'){
        $os = "Linux";
		return $os;
        
    }
}

$user_ip = getIp();
$user_os = getOS();



$iplogdir = "./iplog/";
$iplogfile = "log.dat";

$ipfile = substr(md5($_SERVER["REMOTE_ADDR"]), -2);
$oldtime = 0;
if (file_exists($iplogdir.$ipfile)) $oldtime = filemtime($iplogdir.$ipfile);

$time = time();
if ($oldtime < $time) $oldtime = $time;
$newtime = $oldtime + $iptime;

if ($newtime >= $time + $iptime*$ipmaxvisit)
{
touch($iplogdir.$ipfile, $time + $iptime*($ipmaxvisit-1) + $ippenalty);
$oldref = $_SERVER['HTTP_REFERER'];
header("HTTP/1.0 503 Service Temporarily Unavailable");
header("Connection: close");
header("Content-Type: text/html");
echo "<html><body bgcolor=#0000000000  text=#CCEEFF  link=#ffff00>
<font face='Verdana, Arial'><p><b>
<h1><center>La negación de acceso temporal</h1><center><br><br><br><br><br><br><br><br><br>Vistas rápidas demasiados página por su dirección IP (más que ".$ipmaxvisit." visitas en ".$iptime." segundos)</b>
";
echo "<br /><center>Por Favor Espere ".$ippenalty." Segundos Y Vuelva A Cargar.<br>Tu IP: ".$user_ip."<br>Tu Sistema Operativo: $user_os</p><center></font><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>Powered By AntiDDos By Cuidabrutos.</body></html>";
touch($iplogdir.$iplogfile);
$fp = fopen($iplogdir.$iplogfile, "a");
$yourdomain = $_SERVER['HTTP_HOST'];
   if ($fp)
   {
   $useragent = "<unknown user agent>";
   if (isset($_SERVER["HTTP_USER_AGENT"])) $useragent = $_SERVER["HTTP_USER_AGENT"];
   fputs($fp, $_SERVER["REMOTE_ADDR"]." ".date("d/m/Y H:i:s")." ".$useragent."\n");
   fclose($fp);
   $yourdomain = $_SERVER['HTTP_HOST'];
   
  

   }
   exit();
}
else $_SESSION['reportedflood'] = 0;


touch($iplogdir.$ipfile, $newtime);

?>