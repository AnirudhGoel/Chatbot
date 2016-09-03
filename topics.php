<?php
	// $username_arr = $_POST["username"];
	$username_arr = array("AnirudhGoel", "smart-sachin");
	$repos = array();

	function curl($url) {
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	    curl_setopt_array($ch, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => "Fetch User Repos"
		));
	    
	    $content = curl_exec($ch);
	    curl_close($ch);
	    return $content;
	}

	foreach ($username_arr as $username) {
		$data = curl("https://api.github.com/users/$username/repos");
		$data = json_decode($data, true);
		foreach ($data as $repo) {
			array_push($repos, $repo["html_url"]);
		}
	}

	print_r($repos);
?>