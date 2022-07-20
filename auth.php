<?
include("../config.php");
if(empty($_COOKIE["pass"]) || $_COOKIE["pass"]==""){
	header("Location: login.php");
}
else{
	$per = explode(":", $_COOKIE["pass"]);
	$pass_md5 = $per[0];
	$login = $per[1];
$arq1 = mysql_query("SELECT * From ".$account['table']." WHERE ".$account['name']."='$login'");
$arq = mysql_fetch_array($arq1);
	if($pass_md5 != md5(md5($arq["".$account['pass'].""]))){
		setcookie("pass", "", 0, "/");
		header("Location: login.php");
	}
}
?>