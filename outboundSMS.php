<?php

include ( "src/VoxboneMessage.php" );

//Login and password of the REST_JSON link
$voxbone = new Voxbone('login', 'password');



/*Variables*/
$to = '3246600000'; //Destination
$from = '+32466900539'; //Voxbone VoxDID - Mobible number outbound SMS enabled
$message = 'Hello dolly';
$delivery_report = 'all';// All, error or none,

$fragref= $voxbone->createFragRef(); // or you can set one

$voxbone->sendSMS( $to, $from, $message, $fragref, $fragref);
