<?php
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
?>