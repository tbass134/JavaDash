<?php


	// Well, yeah..
	require('../Postmark.php');
	include('template.php');
	// Create a "server" in your "rack", then copy it's API key
	define('POSTMARKAPP_API_KEY', 'de4b0998-36f3-4428-8f1d-5949ef4653c1');
	
	// Create a "Sender signature", then use the "From Email" here.
	// POSTMARKAPP_MAIL_FROM_NAME is optional, and can be overridden
	// with Mail_Postmark::fromName()
	define('POSTMARKAPP_MAIL_FROM_ADDRESS', 'info@javadash.com');
	define('POSTMARKAPP_MAIL_FROM_NAME', 'JavaDash');
	
	
	$subject ="Name has placed an order using Java Dash";
	$subnav = "Some Subnav";
	$body = '{"Size":"Extra Large","drink":"Coffee","beverage":"Flavored Coffee (please specify)","timestamp":"1319507688","Sweetener":"Sugar","Milk":"1%","drink_type":"Hot","Add Shot of Espresso":true}';
	$userName = "Test";
	$runnerEmail = "Tbass134@gmail.com";
	if($subject != null && $subnav != null && $body != null && $userName != null && $runnerEmail != null)
	{
		sendPostmarkEmail($subject,$subnav,$body,$runnerEmail,$userName);
	}
		
	function sendPostmarkEmail($subject,$subnav,$body,$runnerEmail,$userName)
	{
		$message = generateTemplate($subject,$subnav,$body,$runnerEmail);
		/*
		// Create a message and send it
		Mail_Postmark::compose()
		->addTo($runnerEmail, $userName)
		->subject($subject)
		->messageHtml($message)
		->send();
		*/
		echo $message;
	}
	
?>