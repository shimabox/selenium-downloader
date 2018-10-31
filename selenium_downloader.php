<?php
if (! class_exists('SMB\SeleniumDownloader\Downloader')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use SMB\SeleniumDownloader\Downloader;

$downloader = new Downloader();
$downloader->execute();