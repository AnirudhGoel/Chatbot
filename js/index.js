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
		$(".text").text("");
		$(".chat-body").append("<div class='query'>" + query + "</div>");
		$(".query").last().fadeIn(200);
		$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);

		$(".chat-body").append("<div class='load'><img src='img/loading_dots.gif' class='loader'></div>");
		$(".load").last().fadeIn(1500);
		$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
		
		$.get("../Chatbot/chatbot.php", {q: query}, function(data) {
			data = JSON.parse(data);
			var reply = data["result"];
			$(".load").last().fadeOut(200, function() {
				$(".chat-body").append("<div class='reply'>" + reply + "</div>");
				$(".reply").last().fadeIn(200);
				$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
			});
		})
	}
}