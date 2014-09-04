<?php
	$_sqm_cfg=array(
		"mysql_host"=>"localhost",
		"mysql_db"=>"your_dbname",
		"mysql_user"=>"your_username",
		"mysql_pw"=>"your_mysql_pw",
		"cremote_pw"=>"changeme_38fiwkowow842diwe",
		"auto_sqmc"=>false, /* ALWAYS SET TO FALSE for production sites! */
		"auth_login_request"=>"SELECT id, username, username, email, is_admin FROM users WHERE username=\"%{user}\" AND password=md5(\"%{pw}saltsaltsalt\");",
		"auth_landing"=>"authlanding_url_ext",
	);
?>
