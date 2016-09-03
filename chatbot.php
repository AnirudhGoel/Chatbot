<?php
@$query = $_GET["q"];
$google_key = "AIzaSyA7NWgLZeXIA8leZswLGDFri5NCNZ801VQ";
$video_terms = ["video", "show", "play"];
$flag = 0;

foreach ($video_terms as $video_term) {
	if (stripos($query, $video_term) !== false) {
		$flag = 1;
		$query = str_replace($video_term, "", $query);
	}
}

if ($flag == 1) {
	$data = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&videoCaption=closedCaption&maxResults=1&key=$google_key");

	print_r($data);
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

// echo($response);

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
?>

<!-- https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&videoCaption=closedCaption&key={YOUR_API_KEY} -->
<!-- https://api.havenondemand.com/1/api/sync/extractconcepts/v1?text=+me+of+how+to+do+welding&apikey=c7b3fa19-7afa-43a0-a7bc-034c3b192f5c -->