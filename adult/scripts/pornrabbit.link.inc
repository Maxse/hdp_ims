<?php
	$html = yp_file_get_contents($link);
	include('05_check.file_contents.inc');

    $link = urldecode(str_between($html, '<span class="download"><a href="', '"'));
	include('05_check.link.inc');

	include('07_make.link.inc');
?>