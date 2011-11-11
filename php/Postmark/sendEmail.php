<?php
	
	// Well, yeah..
	require('Postmark.php');
	include('template/template.php');
	// Create a "server" in your "rack", then copy it's API key
	define('POSTMARKAPP_API_KEY', 'de4b0998-36f3-4428-8f1d-5949ef4653c1');
	
	// Create a "Sender signature", then use the "From Email" here.
	// POSTMARKAPP_MAIL_FROM_NAME is optional, and can be overridden
	// with Mail_Postmark::fromName()
	define('POSTMARKAPP_MAIL_FROM_ADDRESS', 'info@javadash.com');
	define('POSTMARKAPP_MAIL_FROM_NAME', 'JavaDash');
		
	function sendPostmarkEmail($subject,$subnav,$body,$userEmail,$userName)
	{
		echo "Calling sendEmail";
		$message = generateTemplate($subject,$subnav,$body,$runnerEmail);
		
		// Create a message and send it
		Mail_Postmark::compose()
		->addTo($userEmail, $userName)
		->subject($subject)
		->messageHtml($message)
		->send();
	}
	
?>