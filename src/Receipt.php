<?php
/**
 * Class Receipt handles and incoming message receipts sent by Voxbone
 * 
 * Usage: $var = new Receipt ();
 * Methods:
 *     exists ( )
 *     
 *
 */
class VoxboneReceipt {
    
    const STATUS_DELIVERED = 'DELIVERED';
	const STATUS_EXPIRED = 'EXPIRED';
	const STATUS_FAILED = 'FAILED';
	const STATUS_BUFFERED = 'BUFFERED';
	
    public $from = '';
	public $to = '';
	
	public $uuid = '';
			
    
    public $delivery_status = '';
	public $status_code = '';
    public $submit_date = '';
    public $done_date = '';
    
    public function __construct () {
	
        if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    		
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        	$file = fopen("test_link.txt","w"); 
            //$obj->{'foo-bar'}
            fwrite($file,"Link: ".$actual_link."\n");
            
            $uuid = substr($actual_link,strrpos($actual_link,"/")+1,strlen($actual_link));
			$actual_link = substr($actual_link,0,strrpos($actual_link,"/report/".$uuid));
    		$to = substr($actual_link,strrpos($actual_link,"/")+1,strlen($actual_link));
            
            echo "UUID :".$uuid;
            echo "<br>Delivery report To : ".$to;
			
            
			$jsonString = file_get_contents("php://input");
			$jsonDecode = json_decode($jsonString);
			
			$this->to = $to;
			$this->from = $jsonDecode->orig_from;
			$this->delivery_status = $jsonDecode->delivery_status;
			$this->status_code = $jsonDecode->status_code;
            $this->submit_date = $jsonDecode->submit_date;
            $this->done_date = $jsonDecode->done_date;
			$this->uuid = $uuid;
    		
            $this->found = true;
			
			return true;
		}
    }
       public function exists () {
        return $this->found;
	}
   
     
   
   
}


