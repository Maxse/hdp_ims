<channel>
<?php
	echo "\t<title>" . $myBaseName . ": 分類選單</title>\r\n";

	if ($pass_check) {
		$siteURLprefix = 'http://www.sexbot.com';
		foreach($videos as $video) {
			$title = trim(str_between($video, ' title="', '"'));
			$title = convertUnicodePoints($title);
			$data  = '';
			$link  = $siteURLprefix . trim(str_between($video, '<div class="category-item-title"><a href="', '"'));
			$image = $siteURLprefix . trim(str_between($video, '<img src="', '"'));
			$note  = '';
			$extra = '';

			include('04_query.inc');
		}
	}
?>
</channel>