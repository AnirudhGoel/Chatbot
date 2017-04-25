<?php
@$query = $_GET["q"];
$google_key = getenv('google_key');
$hp_key = getenv('hp_key');
$pandora_bot_id = getenv('pandora_bot_id');
$pandora_bot_url = "https://www.pandorabots.com/pandora/talk?botid=".$pandora_bot_id;
$flag = 0;
$video_terms = ["video", "show", "play", "demonstrate"];
$define_terms = ["define", "what is", "what's", "definition of"];
$game_terms = ["open quiz", "i am bored", "open game"];
$final = ["result" => "failed"];

foreach ($video_terms as $video_term) {
	if (stripos($query, $video_term) !== false) {
		$flag = 1;
		$query = str_ireplace($video_term, "", $query);
	}
}

foreach ($define_terms as $define_term) {
	if (stripos($query, $define_term) !== false) {
		$flag = 2;
		$query = str_ireplace($define_term, "", $query);
	}
}

// Additional Case for mobile app
foreach ($game_terms as $game_term) {
	if (strcasecmp(trim($query), $game_term) == 0) {
		$flag = 3;
	}
}

$query = urlencode($query);

if ($flag == 1) {
	$data = file_get_contents("https://api.havenondemand.com/1/api/sync/extractconcepts/v1?text=$query&apikey=$hp_key");
	$data = json_decode($data, true);
	$concept = $data["concepts"][0]["concept"];
	$concept = urlencode($concept);
	$data = file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q=$concept&type=video&videoCaption=closedCaption&maxResults=1&key=$google_key");
	$data = json_decode($data, true);
	$video_id = $data["items"][0]["id"]["videoId"];
	$final["result"] = "https://www.youtube.com/watch?v=".$video_id;
} else if ($flag == 2) {
	$data = file_get_contents("https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=$query");
	$data = json_decode($data, true);
	foreach ($data["query"]["pages"] as $page) {
		if (array_key_exists("extract", $page) && $page["extract"] != "") {
			$long = htmlspecialchars($page["extract"]);
			$sentences = explode(". ", $long);
			$sentences = array_slice($sentences, 0, 3);
			$response = implode(". ", $sentences);
			$final["result"] = $response;
		} else {
			$final["result"] = "Let me Google that for you - http://lmgtfy.com/?q=$query";
		}
	}
} else if ($flag == 3) {
	$final["result"] = "101";
} else {
	$url = $pandora_bot_url;
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
	if ($result == FALSE) {
		$final["result"] = "Umm.. Something doesn't look right.. Wait ! Let me Google that for you - http://lmgtfy.com/?q=$query";
	} else {
		$parsed = get_string_between($result, 'The Professor:', '</font>');
		$response = str_ireplace("<br/><br/>", "", $parsed);
		$response = substr($response, 8);
		$final["result"] = $response;
	}
}

print_r(json_encode($final, true));

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
?>