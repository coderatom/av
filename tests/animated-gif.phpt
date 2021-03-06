--TEST--
Animated GIF test
--SKIPIF--
<?php 
	if(!function_exists('imagecreatetruecolor')) print 'skip GD not available';
	if(!in_array('gif', av_get_encoders())) print 'skip GIF encoder not avilable';
?>
--FILE--
<?php

require("helpers.php");

$folder = dirname(__FILE__);
$filename = "test.gif";

$testVideo = new TestVideo("$folder/$filename", 640, 480, 24, 5.0);
$testVideo->setAudioCodec(false);
$testVideo->create();
$testVideo->verify();
$testVideo->delete();

echo "OK\n";

?>
--EXPECT--
OK
