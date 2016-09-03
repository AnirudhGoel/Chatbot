<?php
@$query = $_GET["q"];

if (strpos($query, "video")) {
	
} else {
	$url = 'https://www.pandorabots.com/pandora/talk?botid=935a0a567e34523c';
	$data = array("input" => urlencode($query), "questionstring" => "Select+a+question", "submit" => "Ask+The+Professor", "botcust2" => "d028c08f5f391562");

	// use key 'http' even if you send the request to https://...
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query($data)
	    )
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }

	$parsed = get_string_between($result, 'The Professor:', '</font>');

	$response = str_replace("<br/><br/>", "", $parsed);
}
echo($response);

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
?>

<!-- google_key = AIzaSyA7NWgLZeXIA8leZswLGDFri5NCNZ801VQ -->
<!-- https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&videoCaption=closedCaption&key={YOUR_API_KEY} -->