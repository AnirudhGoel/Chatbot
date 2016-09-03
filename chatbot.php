<?php
@$query = $_GET["q"];
$google_key = "AIzaSyA7NWgLZeXIA8leZswLGDFri5NCNZ801VQ";
$hp_key = "c7b3fa19-7afa-43a0-a7bc-034c3b192f5c";
$video_terms = ["video", "show", "play"];
$flag = 0;

foreach ($video_terms as $video_term) {
	if (stripos($query, $video_term) !== false) {
		$flag = 1;
		$query = str_replace($video_term, "", $query);
	}
}

if (stripos($query, "define") !== false) {
	$flag = 2;
	$query = str_replace("define", "", $query);
}

if (stripos($query, "what is") !== false) {
	$flag = 2;
	$query = str_replace("what is", "", $query);
}

if (stripos($query, "Manmohan") !== false) {
	$flag = 3;
}

if ($flag == 1) {
	$query = urlencode($query);
	$data = file_get_contents("https://api.havenondemand.com/1/api/sync/extractconcepts/v1?text=$query&apikey=$hp_key");
	$data = json_decode($data, true);
	$concept = $data["concepts"][0]["concept"];
	$concept = urlencode($concept);

	$data = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q=$concept&type=video&videoCaption=closedCaption&maxResults=1&key=$google_key");
	$data = json_decode($data, true);
	$video_id = $data["items"][0]["id"]["videoId"];

	echo("https://www.youtube.com/watch?v=".$video_id."vid");
} else if ($flag == 2) {
	$data = file_get_contents("https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=".urlencode($query));
	$data = json_decode($data, true);
	foreach ($data["query"]["pages"] as $page) {
		if (array_key_exists("extract", $page) && $page["extract"] != "") {
			$long = htmlspecialchars($page["extract"]);
			$sentences = explode(". ", $long);
			$sentences = array_slice($sentences, 0, 3);
			$response = implode(". ", $sentences);
			echo($response);
		} else {
			echo("Umm.. Something doesn't look right.. Wait ! Let me Google that for you - http://lmgtfy.com/?q=".urlencode($query));
		}
	}
} else if ($flag == 3) {
	echo("https://media.licdn.com/mpr/mpr/shrinknp_200_200/p/8/000/20c/0da/06db71f.jpg");
} else {
	$url = 'https://www.pandorabots.com/pandora/talk?botid=935a0a567e34523c';
	$data = array("input" => urlencode($query), "questionstring" => "Select+a+question", "submit" => "Ask+The+Professor", "botcust2" => "d028c08f5f391562");

	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query($data)
	    )
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { 
		echo("Umm.. Something doesn't look right.. Wait ! Let me Google that for you - http://lmgtfy.com/?q=".urlencode($query));
	}

	$parsed = get_string_between($result, 'The Professor:', '</font>');

	$response = str_replace("<br/><br/>", "", $parsed);
	echo($response);
}


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