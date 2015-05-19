<?php
//Receive delivery report
include ( "src/Receipt.php" );
            
            $receipt = new VoxboneReceipt();
            $file = fopen("test_delivery.txt","w"); 
            //$obj->{'foo-bar'}
            fwrite($file,"To: ".$receipt->to."\n");
            fwrite($file,"From: ".$receipt->from."\n"); 
            fwrite($file,"uuid: ".$receipt->uuid."\n"); 
            fwrite($file,"delivery status: ".$receipt->delivery_status."\n"); 
            fwrite($file,"status code: ".$receipt->status_code."\n"); 
            fwrite($file,"submit_date: ".$receipt->submit_date."\n"); 
            fwrite($file,"submit_date: ".$receipt->done_date."\n"); 
            fclose($file);
        
