<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/vnd.heroku+json; version=3"));
curl_setopt($ch, CURLOPT_URL, "https://api.heroku.com/apps/edu-chatbot/config-vars");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
if ($curl_errno == 0) {
    echo($data);
} else {
	echo "error";
}

?>