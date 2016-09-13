function checkEnter(event) {
	if(event.keyCode == 13) {
		return "true";
	}
}
function reply(event) {
	var data = "";
	console.log("Hello");
	if (checkEnter(event) == "true") {
		var query = $(".text").text();
		$(".chat-body").append("<div class='query'>" + query + "</div>");
		$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
		console.log(query);
		$(".text").text("");
		$.get("../Chatbot/chatbot.php", {q: query}, function(data) {
			data = JSON.parse(data);
			var reply = data["result"];
			$(".chat-body").append("<div class='reply'>" + reply + "</div>");
			$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
		})
	}
}