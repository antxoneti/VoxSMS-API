<?php
/**
 * Class Voxbone handles the methods and properties of sending an SMS message.
 * 
 * Usage: $voxbone = new Voxbone ( $account_key, $account_password );
 * Methods:
 *     voxbone.sendSMS ( $to, $from, $message, $fragref )

 */

class Voxbone {//_api?
	// Voxbone account credentials
	private $login = '';
	private $password = '';
	
	//For inbound
	var $inbound_message = false;
	// Current message
	public $to = '';
	public $from = '';
	public $message = '';
	public $fragmentation = '';
	public $uuid = [];
	
	
	
	function Voxbone ($login, $password) {
		$this->login = $login;
		$this->password = $password;
	}
	function sendSMS ( $to, $from, $message, $fragref) {
		$maxchar = $this->getFragLength($message, $fragref);
		echo $maxchar;
		echo strlen($message);
		$fragments = [];
		if (strlen($message) > $maxchar){
	            while (strlen($message) > $maxchar){
			echo "<br>".$message;
			array_push($fragments,substr($message,0,$maxchar));
	                $message = substr($message,$maxchar);
			if (strlen($message) < $maxchar){
	                 echo "<br>".$message;
			 array_push($fragments,$message);
	                }
	            }
		}else{   
			array_push($fragments,$message);
	        }
		if(count($fragments) > 1){
	            for ($i = 0; $i < count($fragments); ++$i) {
	              $frag = ["frag_ref" => $fragref, "frag_total" => count($fragments), "frag_num" => $i+1];
	              $data =["from" => $from, "msg" => $fragments[$i], "frag" => $frag, "delivery_report" => "none"];
	              $postdata = json_encode($data);
		      $this -> uuid[i] = sendSMSRequest('https://be.sms.voxbone.com:4443/sms/v1/'.$to, $postdata);    
	            }
	        }else{
	            $frag = null;
	            $data = ["from" => $from, "msg" => $message, "frag" => $frag, "delivery_report" => "none"];
	            $postdata = json_encode($data);
		    $this -> uuid[0] = $this->sendSMSRequest('https://be.sms.voxbone.com:4443/sms/v1/'.$to, $postdata);
		}
	}
	private function getFragLength($message, $fragref){
		$encoding = mb_detect_encoding($message, "auto");
		if ($encoding == 'ASCII'){
			if (strlen($message) <= 160){
		    		$maxchar = 160;
			}else if (strlen($message) >160 && $fragref <= 255){
		    		$maxchar = 153;
			}else if (strlen($message) > 160 && $fragref > 255){
		    		$maxchar = 152;      
			}
		}else if ($encoding == 'UTF-8'){
	     	if (strlen($message) <= 140){
	            $maxchar = 140;
		}
		else if (strlen($message) >140 && $fragref <= 255){
	     		$maxchar = 134;
		}else if (strlen($message) > 140 && $fragref > 255){
	            $maxchar = 133;      
		}
		}else if ($encoding == 'ucs-2'){
			if (strlen($message) <= 70 && $fragref == null ){
	            		$maxchar = 70;
			}
			else if (strlen($message) > 70 && $fragref <= 255){
				$maxchar = 65;
			}else if (strlen($message) > 70 && $fragref > 255){
	            		$maxchar = 64;      
		    	}
		}
	  	return $maxchar;
	  }
	private function sendSMSrequest($url, $postdata){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $this->login.":".$this->password);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$verbose = fopen('php://temp', 'rw+');
		curl_setopt($ch, CURLOPT_STDERR, $verbose);
		//$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$result=curl_exec ($ch);
		$result_decode = json_decode($json_result);
		$result = $result_decode->transaction_id;
		if ($result === FALSE) {
			printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
			htmlspecialchars(curl_error($ch)));
		}
		rewind($verbose);
		$verboseLog = stream_get_contents($verbose);
		echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
		curl_close ($ch);
	  }

	  public function inboundmessage( $data=null ){
			
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$to = substr($actual_link,strrpos($actual_link,"/")+1,strlen($actual_link));
			
			$jsonString = file_get_contents("php://input");
			$jsonDecode = json_decode($jsonString);
			
			$this->to = $to;
			$this->from = $jsonDecode->from;
			$this->message = $jsonDecode->msg;
			$this->uuid = $jsonDecode->uuid;
			//frag		
		    // Flag that we have an inbound message
			$this->inbound_message = true;
			
			return true;
		}	  
	}
	
}	
