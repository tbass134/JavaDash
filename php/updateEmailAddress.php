<?php
require('inc/functions.php');

$email = $_POST['email'];

if($email == null)
{
	$message =  "no email address";
}
else
{
	$user = findUserByEmail($email);
	if($user != null)
	{
		$sql = "UPDATE users SET enable_email_use=0 WHERE id={$user->id}";
		dbUpdate($sql);
		$message = "Thank You - You have been unsubscribed from JavaDash!";
	}
	else
		$message =  "User was not found in Database";
}

echo "<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
<TITLE>Change </TITLE>
</HEAD>
<body  bgcolor=''>
<div align='center'> 
  <center>
    <TABLE border=0 cellPadding=0 cellSpacing=11>
      <TBODY> 
      <TR> 
        <TD height=70 valign='top' align='center'> 
          <center></center>
        </TD>
      </TR>
      <TR valign='middle'> 
        <TD height=59 align='center'><font color='' face='' size=''>".$message."
<br></font></TD>
      </TR>
      </TBODY> 
    </TABLE>
  </center>
</div>
</BODY>
</HTML>
"
?>




