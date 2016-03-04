<?php
/**
 * 转一个文档，超时则停止，以免卡主
 */
set_time_limit(60);

//require 'define.php';

define( 'EXE_PATH' , 'D:\jpeg\officeConvert' );
$argv = array( 1 => '' );

officeConvert($argv);

function officeConvert($argv) {
	$file = $argv[1];
	$dir = $argv[2];
	
	$cmd = EXE_PATH . DIRECTORY_SEPARATOR . "OfficeConvert $file $dir little";
	exec($cmd);
	unlink($file);
}


