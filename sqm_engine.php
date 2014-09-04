<?php
	include_once("sqm_settings.php");

	$_sqm_error="";
	function _sqm_add_error($what) { global $_sqm_error; $_sqm_error.="SQM :: ".$what."<br />"; }

	$_sqm_ifurl_found=false;

	$_sqm_mail_additional_headers="";
	
	$_sqm_php_baseurl=implode("/",explode("/",$_SERVER["PHP_SELF"],-1));
	$_sqm_php_endurl=substr(explode("?",$_SERVER["REQUEST_URI"])[0],strlen($_sqm_php_baseurl)+1);


	//Estabilish SQL connection
	$_sqm_dbconn=mysql_connect($_sqm_cfg["mysql_host"], $_sqm_cfg["mysql_user"], $_sqm_cfg["mysql_pw"]);
	if(!$_sqm_dbconn) _sqm_add_error("Can't create MySQL connection.");
	if(!mysql_select_db($_sqm_cfg["mysql_db"],$_sqm_dbconn)) _sqm_add_error("Can't select MySQL database.");

	function _sqm_recompile()
	{
		global $_sqm_cfg, $_sqm_coutput;
		if($_sqm_cfg["auto_sqmc"]) 
		{
			$_sqm_coutput=shell_exec("./sqmc 2>&1");
			$_sqm_cfg["auto_sqmc"]=false;
			return true;
		}
		return false;
	}


/*          _   _    
  __ _ _  _| |_| |_  
 / _` | || |  _| ' \ 
 \__,_|\_,_|\__|_||_|
*/  
                   
	session_start();
	if(!isset($_SESSION["_sqm_user"])) _sqm_resetuser();

	function _sqm_resetuser()
	{
		$_SESSION["_sqm_user"]="";
		$_SESSION["_sqm_user_id"]=-1;
		$_SESSION["_sqm_user_isadmin"]=false;
		$_SESSION["_sqm_user_loggedin"]=false;
		$_SESSION["_sqm_user_email"]="";
		$_SESSION["_sqm_auth_fail"]=false;
	}

	function _sqm_land_auth()
	{
		global $_sqm_php_baseurl, $_sqm_cfg;
		header("Location: ".$_sqm_php_baseurl."/".$_sqm_cfg["auth_landing"]);
		die();
	}

	function _sqm_tryauth()
	{
		if($_SESSION["_sqm_user_loggedin"]) _sqm_land_auth(); 
		global $_sqm_cfg, $_sqm_dbconn;
		if (!isset($_POST["pw"])||!isset($_POST["user"])) return;
		$_sqm_auth_result=mysql_query(
			str_replace("%{pw}",mysql_real_escape_string($_POST["pw"]),
			str_replace("%{user}",mysql_real_escape_string($_POST["user"]),
			$_sqm_cfg["auth_login_request"])),$_sqm_dbconn); 
		if(!$_sqm_auth_result) _sqm_add_error("MySQL error while trying to authenticate:".mysql_error());
		else {
			$_sqm_auth_row=mysql_fetch_row($_sqm_auth_result); 
			if(mysql_fetch_row($_sqm_auth_result))
			{
				_sqm_add_error("Database structure error: duplicate records exist for this username. Won't log in.");
				return;
			}
			if($_sqm_auth_row)
			{
				$_SESSION["_sqm_user"]=$_sqm_auth_row[1];
				$_SESSION["_sqm_user_id"]=$_sqm_auth_row[0];
				$_SESSION["_sqm_user_nick"]=$_sqm_auth_row[2];
				$_SESSION["_sqm_user_email"]=$_sqm_auth_row[3];
				$_SESSION["_sqm_user_isadmin"]=$_sqm_auth_row[4];
				$_SESSION["_sqm_user_loggedin"]=true;
				_sqm_land_auth();
			}
		}
		$_SESSION["_sqm_auth_fail"]=true;
	}

	if($_SESSION["_sqm_auth_fail"]&&$_SERVER['REQUEST_METHOD']=="GET") $_SESSION["_sqm_auth_fail"] = false;

?>
