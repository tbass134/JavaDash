<?php
//echo generateTemplate("test","Subnav Text here","test");
function generateTemplate($subject,$subnav_text,$order,$runnerEmail)
{

	if($_SERVER['SERVER_NAME'] == "javadash.com")
	{
		$basedomain = "http://javadash.com/JavaDash/php/Postmark/template/";
		$script_loc = "http://javadash.com/JavaDash/php/Postmark/RemoveFromList/index.php";
	}
	else
	{
		$basedomain = "http://dev.javadash.com/JavaDash/php/Postmark/template/";
		$script_loc = "http://dev.javadash.com/JavaDash/php/Postmark/RemoveFromList/index.php";
	}
	$myOrder = decode_order($order);
		
	$message =  '
	<html>
	<head>
		<title>Java Dash</title>
		<style>
		a:hover {
			text-decoration: underline !important;
		}
		td.promocell p { 
			color:#e1d8c1;
			font-size:16px;
			line-height:26px;
			font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;
			margin-top:0;
			margin-bottom:0;
			padding-top:0;
			padding-bottom:14px;
			font-weight:normal;
		}
		td.contentblock h4 {
			color:#444444 !important;
			font-size:16px;
			line-height:24px;
			font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;
			margin-top:0;
			margin-bottom:10px;
			padding-top:0;
			padding-bottom:0;
			font-weight:normal;
		}
		td.contentblock h4 a {
			color:#444444;
			text-decoration:none;
		}
		td.contentblock p { 
			color:#888888;
			font-size:13px;
			line-height:19px;
			font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;
			margin-top:0;
			margin-bottom:12px;
			padding-top:0;
			padding-bottom:0;
			font-weight:normal;
		}
		td.contentblock p a { 
			color:#3ca7dd;
			text-decoration:none;
		}
		@media only screen and (max-device-width: 480px) {
			 div[class="header"] {
				  font-size: 16px !important;
			 }
			 table[class="table"], td[class="cell"] {
				  width: 300px !important;
			 }
			table[class="promotable"], td[class="promocell"] {
				  width: 325px !important;
			 }
			td[class="footershow"] {
				  width: 300px !important;
			 }
			table[class="hide"], img[class="hide"], td[class="hide"] {
				  display: none !important;
			 }
			 img[class="divider"] {
				  height: 1px !important;
			 }
			 td[class="logocell"] {
				padding-top: 15px !important; 
				padding-left: 15px !important;
				width: 300px !important;
			 }
			 img[id="screenshot"] {
				  width: 325px !important;
				  height: 127px !important;
			 }
			img[class="galleryimage"] {
				  width: 53px !important;
				  height: 53px !important;
			}
			p[class="reminder"] {
				font-size: 11px !important;
			}
			h4[class="secondary"] {
				line-height: 22px !important;
				margin-bottom: 15px !important;
				font-size: 18px !important;
			}
		}
		</style>
	</head>
	<body bgcolor="#e4e4e4" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="-webkit-font-smoothing: antialiased;width:100% !important;background:#e4e4e4;-webkit-text-size-adjust:none;">
		
	<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e4e4e4">
	<tr>
		<td bgcolor="#e4e4e4" width="100%">
	
		<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
		<tr>
			<td width="600" class="cell">
			
			<table width="600" cellpadding="0" cellspacing="0" border="0" class="table">
			<tr>
				<td width="250" bgcolor="#e4e4e4" class="logocell"><img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="20" class="hide"><br class="hide"><img src="'.$basedomain.'/images/widget-logo4.png" width="178" height="76" alt="Java Dash" style="-ms-interpolation-mode:bicubic;"><br><img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="10" class="hide"><br class="hide"></td>
				<td align="right" width="350" class="hide" style="color:#a6a6a6;font-size:12px;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;text-shadow: 0 1px 0 #ffffff;" valign="top" bgcolor="#e4e4e4"><img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="63"><br><span>Java Dash for iPhone&nbsp;</span><strong><span style="text-transform:uppercase;"> <currentmonthname> <currentyear></span></strong> <span>&nbsp;</span></td>
			</tr>
			</table>
		
			<img border="0" src="'.$basedomain.'/images/widget-hero3.png" label="Hero image" editable="true" width="600" height="253" id="screenshot">
		
			<table width="600" cellpadding="25" cellspacing="0" border="0" class="promotable">
			<tr>
				<td bgcolor="#456265" width="600" class="promocell">                      
				 
					<multiline label="Main feature intro"><p>'.$subject.'</p></multiline>
				
				</td>
			</tr>
			</table>
		
			<img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="15" class="divider"><br>
		
			<repeater>
				<layout label="New feature">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td bgcolor="#85bdad" nowrap><img border="0" src="'.$basedomain.'/images/spacer.gif" width="5" height="1"></td>
					<td width="100%" bgcolor="#ffffff">
				
						<table width="100%" cellpadding="20" cellspacing="0" border="0">
						<tr>
							<td bgcolor="#ffffff" class="contentblock">
	
								<h4 class="secondary"><strong><singleline label="Title">'.$subnav_text .'</singleline></strong></h4>
								<multiline label="Description"><p>'. $myOrder .'</p></multiline>
	
							</td>
						</tr>
						</table>
				
					</td>
				</tr>
				</table>
				</layout>
				
			</repeater>           
			
			</td>
		</tr>
		</table>
	
		<img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="25" class="divider"><br>
	
		<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f2f2f2">
		<tr>
			<td>
			
				<img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="30"><br>
			
				<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
				<tr>
					<td width="600" nowrap bgcolor="#f2f2f2" class="cell">
					
						<table width="600" cellpadding="0" cellspacing="0" border="0" class="table">
						<tr>
							<td width="380" valign="top" class="footershow">
							
								<img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="8"><br>  
							
								<p style="color:#a6a6a6;font-size:12px;font-family:Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:15px;padding-top:0;padding-bottom:0;line-height:18px;" class="reminder">You’re receiving this email because this address was added to our user list. If you would like to stop receiving these emails, please <a href="'.$script_loc.'?email='.$runnerEmail.'" style="color:#a6a6a6;text-decoration:underline;">click here</a>.</p>
								<p style="color:#c9c9c9;font-size:12px;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;"><preferences style="color:#3ca7dd;text-decoration:none;"><strong></strong></preferences><unsubscribe style="color:#3ca7dd;text-decoration:none;"><strong>Unsubscribe instantly</strong></unsubscribe></p>
							
							</td>
							<td align="right" width="220" style="color:#a6a6a6;font-size:12px;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;text-shadow: 0 1px 0 #ffffff;" valign="top" class="hide">
							
								<table cellpadding="0" cellspacing="0" border="0">
								<tr>
							
									<td><a href="http://www.facebook.com/pages/Java-Dash/165953236812659"><img border="0" src="'.$basedomain.'/images/facebook.gif" width="32" height="32" alt="Visit us on Facebook"></a><a href="http://twitter.com/JavaDashApp"><img border="0" src="'.$basedomain.'/images/twitter.gif" width="42" height="32" alt="Follow us on Twitter"></a></td>
								</tr>
								</table>
							
								<img border="0" src="'.$basedomain.'/images/spacer.gif" width="1" height="10"><br><p style="color:#b3b3b3;font-size:11px;line-height:15px;font-family:Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;font-weight:bold;"></p><p style="color:#b3b3b3;font-size:11px;line-height:15px;font-family:Helvetica,Arial,sans-serif;margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;font-weight:normal;"></p></td>
						</tr>
						</table>
					
					</td>
				</tr>	
				</table>
	
			
		   </td>
		</tr>
		</table>
		
		</td>
	</tr>
	</table>  	   			     	 
	
	</body>
	</html>';
	
	
	return $message;
}


function decode_order($json)
{
	$order_str = '';
	$json = json_decode($json);
	foreach ($json as $key => $value) {
	
		if($key != "timestamp")
		$order_str .= "$key: $value<br />\n";
	}	
	
	return ucwords($order_str);
}
?>