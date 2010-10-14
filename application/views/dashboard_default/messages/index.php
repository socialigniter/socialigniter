<?php 
/*
$array = array(
	'content_id'	=> 1, 
	'url'			=> 'http://social-igniter:8890',
	'title'			=> 'Welcome',
	'description'	=> 'You gotta wonder sometimes why the bluebird flies and the blackbird crows! There are things in life that make little sense. That...'
);
*/
$array = array(
	"status"		=> 'Yay honey, you are never gonna guess where or what this is :)',
	"content_id"	=> 59,
	"title"			=> "Super ROMANCE Date :)",
	"url"			=> "http://social-igniter.com/events/view/59",
	"date_time"		=> "2010-11-12T03:02:00+0000",
	"location"		=> array('name' => 'Secret Spot')
);

$json = json_encode($array);

echo $json;
echo '<hr>';

?>