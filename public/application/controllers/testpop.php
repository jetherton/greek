<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Json Controller
 * Generates Map GeoJSON File
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package	   Ushahidi - http://source.ushahididev.com
 * @subpackage Controllers
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */


class Testpop_Controller extends Template_Controller {

	/**
	 * Disable automatic rendering
	 * @var bool
	 */
	public $auto_render = FALSE;

	/**
	 * Template for this controller
	 * @var string
	 */
	public $template = '';

	
	
	public function index()
	{
		
		$this->auto_render = false;
		$this->template = '';
		
		
		echo '<HTML><HEAD><TITLE>Test for Manuel Lemos\'s PHP POP3 class</TITLE></HEAD><BODY>'; 
		
	$pop3=new Pop3();
	$pop3->hostname="pop.secureserver.net";             /* POP 3 server host name                      */
	$pop3->port=110;                         /* POP 3 server host port,
	                                            usually 110 but some servers use other ports
	                                            Gmail uses 995                              */
	$pop3->tls=0;                            /* Establish secure connections using TLS      */
	$user="noreply@crv-greece.org";                        /* Authentication user name                    */
	$password="dontbotherreplyingtome";                    /* Authentication password                     */
	$pop3->realm="";                         /* Authentication realm or domain              */
	$pop3->workstation="";                   /* Workstation for NTLM authentication         */
	$apop=0;                                 /* Use APOP authentication                     */
	$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
	$pop3->debug=0;                          /* Output debug information                    */
	$pop3->html_debug=1;                     /* Debug information is in HTML                */
	$pop3->join_continuation_header_lines=1; /* Concatenate headers split in multiple lines */

	if(($error=$pop3->Open())=="")
	{
		echo "<PRE>Connected to the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
		if(($error=$pop3->Login($user,$password,$apop))=="")
		{
			echo "<PRE>User &quot;$user&quot; logged in.</PRE>\n";
			if(($error=$pop3->Statistics($messages,$size))=="")
			{
				echo "<PRE>There are $messages messages in the mail box with a total of $size bytes.</PRE>\n";
				$result=$pop3->ListMessages("",0);
				if(GetType($result)=="array")
				{
					for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
						echo "<PRE>Message ",Key($result)," - ",$result[Key($result)]," bytes.</PRE>\n";
					$result=$pop3->ListMessages("",1);
					if(GetType($result)=="array")
					{
						for(Reset($result),$message=0;$message<count($result);Next($result),$message++)
							echo "<PRE>Message ",Key($result),", Unique ID - \"",$result[Key($result)],"\"</PRE>\n";
						if($messages>0)							
						{
							
							if(($error=$pop3->RetrieveMessage(1,$headers,$body,-1, $raw))=="")
							{
								echo "<pre>".htmlspecialchars($raw)."</pre>";
								/*
								echo "<PRE>Message 1:\n</PRE>\n";
								for($line=0;$line<count($headers);$line++)
									echo "<PRE>",HtmlSpecialChars($headers[$line]),"</PRE>\n";
								echo "<PRE>---Message headers ends above---\n---Message body starts below---</PRE>\n";
								for($line=0;$line<count($body);$line++)
								{
									echo "<PRE>",HtmlSpecialChars($body[$line]),"</PRE>\n";
									//echo $this->decode7Bit(substr($body[$line],strlen("--------------")));
								}
								*/
								$emailParser = new Mime($raw);
								
								$to = $emailParser->getTo();
								echo "<p>to: ";
								print_r($to);
								echo "</p>";
								$to = $emailParser->getSubject();
								echo "<p>Subject: ";
								print_r($to);
								echo "</p>";
								
								echo "<p>body: ";
								$emailBody = $emailParser->getPlainBody();
								echo "</p>";
								/*
								echo "<PRE>---Message body ends above---</PRE>\n";
								if(($error=$pop3->DeleteMessage(1))=="")
								{
									echo "<PRE>Marked message 1 for deletion.</PRE>\n";
									if(($error=$pop3->ResetDeletedMessages())=="")
									{
										echo "<PRE>Resetted the list of messages to be deleted.</PRE>\n";
									}
								}
								*/
							}
						}

						if($error=="" && ($error=$pop3->Close())=="")
						{
							echo "<PRE>Disconnected from the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
						}
						
					}
					else
						$error=$result;
				}
				else
					$error=$result;
			}
		}
	}
	if($error!="")
		echo "<H2>Error: ",HtmlSpecialChars($error),"</H2>";
	
	echo '</BODY></HTML>';
	

	}//end method
	
	
	public function decode7Bit($text) {
		// If there are no spaces on the first line, assume that the body is
		// actually base64-encoded, and decode it.
		$lines = explode("\r\n", $text);
		$first_line_words = explode(' ', $lines[0]);
		if ($first_line_words[0] == $lines[0]) {
			$text = base64_decode($text);
		}
	
		// Manually convert common encoded characters into their UTF-8 equivalents.
		$characters = array(
				'=20' => ' ', // space.
				'=E2=80=99' => "'", // single quote.
				'=0A' => "\r\n", // line break.
				'=A0' => ' ', // non-breaking space.
				'=C2=A0' => ' ', // non-breaking space.
				"=\r\n" => '', // joined line.
				'=E2=80=A6' => '…', // ellipsis.
				'=E2=80=A2' => '•', // bullet.
		);
	
		// Loop through the encoded characters and replace any that are found.
		foreach ($characters as $key => $value) {
			$text = str_replace($key, $value, $text);
		}
	
		return $text;
	}
	
	public function test()
	{
		/* Set connection options */
		$pop3=new Pop3();
		$pop3->hostname="pop.secureserver.net";             /* POP 3 server host name                      */
		$pop3->port=110;                         /* POP 3 server host port,
	                                            usually 110 but some servers use other ports
		                                            Gmail uses 995                              */		
		$user="noreply@crv-greece.org";                        /* Authentication user name                    */
		$password="dontbotherreplyingtome";                    /* Authentication password                     */
		$apop=0;                                 /* Use APOP authentication                     */		
		
		/* Connect to the server */
		if(($error = $pop3->Open())=="")
		{
		
			/* Authenticate */
			if(($error = $pop3->Login($user, $password, $apop))=="")
			{
		
				/* Setup a file name of a message to be retrieved
				 * on an already opened POP3 connection */
				$pop3->GetConnectionName($connection_name);
				$message=1;
				$message_file='pop3://'.$connection_name.'/'.$message;
		
				echo $message_file;
				/* Do your message processing here */
				$message = file_get_contents($message_file);
		
				echo $message;
				/* If all goes well, delete the processed message */
				//$pop3->DeleteMessage($message);
			}
		
			/* Close the connection before you exit */
			$pop3->Close();
		}
		
	}
	
	
}