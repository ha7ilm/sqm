<?php 
/* You can use this script to remotely run sqmc, which might be useful during development.

- You have to pass the password on request URI, like this:
	http://yourhost.org/sqm_cremote.php?cremote_password_set_in_sqm_settings
- You have to give write permissions to Apache user for these files 
  (however, you decide if it is a good idea from the security side).

However, the "auto_sqmc" setting is an easier alternative to this, just don't forget to 
switch it off  for the ready site.

*/


<h1>Remotely running <em>sqmc</em>...</h1>

<pre><?php
		include("sqm_settings.php");
		$parts=explode("?",$_SERVER["REQUEST_URI"]);
		if(!isset($parts[1]) or $parts[1]=="" or $_sqm_cfg["cremote_pw"]!=$parts[1]) die("Access denied.");
		echo(htmlspecialchars(shell_exec("./sqmc 2>&1"))); ?></pre>
