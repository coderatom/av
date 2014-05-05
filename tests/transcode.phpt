--TEST--
Transcode test
--SKIPIF--
<?php 
	if(!function_exists('imagecreatetruecolor')) print 'skip GD not available';
?>
--FILE--
<?php

$folder = dirname(__FILE__);
$image = imagecreatetruecolor(852, 480);
$file_in = av_file_open("$folder/source-code.mkv", "r");
$stat_in = av_file_stat($file_in);
$file_out1 = av_file_open("$folder/source-code.mp4", "w", array( "metadata" => $stat_in["metadata"] ));
$file_out2 = av_file_open("$folder/source-code.webm", "w");


$a_strm_in = av_stream_open($file_in, "audio");
$v_strm_in = av_stream_open($file_in, "video");

$a_strm_out1 = av_stream_open($file_out1, "audio");
$v_strm_out1 = av_stream_open($file_out1, "video", array( "width" => imagesx($image), "height" => imagesy($image) ));

$a_strm_out2 = av_stream_open($file_out2, "audio");
$v_strm_out2 = av_stream_open($file_out2, "video", array( "width" => imagesx($image), "height" => imagesy($image) ));


if(!$file_in || !$file_out1 || !$file_out2 || !$a_strm_in || !$a_strm_out1 || !$a_strm_out2 || !$v_strm_in || !$v_strm_out1 || !$v_strm_out2) {
	die();
}

$v_time = 0;
$a_time = 0;

while(!av_file_eof($file_in)) {
	if($v_time < $a_time || !isset($a_strm_in)) {
		if(av_stream_read_image($v_strm_in, $image, $v_time)) {
			av_stream_write_image($v_strm_out1, $image, $v_time);
			av_stream_write_image($v_strm_out2, $image, $v_time);
		} else {
			printf("%.4f\n", $v_time);
			$v_time = INF;
		}
	} else {
		if(av_stream_read_pcm($a_strm_in, $pcm, $a_time)) {
			av_stream_write_pcm($a_strm_out1, $pcm, $a_time);
			av_stream_write_pcm($a_strm_out2, $pcm, $a_time);
		} else {
			printf("%.4f\n", $a_time);
			$a_time = INF;
		}
	}
}

av_file_close($file_out1);
av_stream_close($a_strm_out1);
av_stream_close($v_strm_out1);

av_file_close($file_out2);
av_stream_close($a_strm_out2);
av_stream_close($v_strm_out2);

av_file_close($file_in);
av_stream_close($a_strm_in);
av_stream_close($v_strm_in);

?>
--EXPECT--
29.8220