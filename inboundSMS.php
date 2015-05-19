<?php

include ("src/VoxboneMessage.php" );

//Login and password of your link group
$voxbone = new Voxbone('login', 'password');

//receive a SMS

if ($voxbone->inboundmessage()) {
    
    $file = fopen("test_inboundSMS.txt","w"); 
    //$obj->{'foo-bar'}
    fwrite($file,"To: ".$voxbone->to."\n");
    fwrite($file,"From: ".$voxbone->from."\n"); 
    fwrite($file,"uuid: ".$voxbone->uuid."\n"); 
    fwrite($file,"message: ".$voxbone->msg."\n"); 
    fclose($file); 
}
