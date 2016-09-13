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
		$(".query").fadeIn(1000);
		$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);

		$(".chat-body").append("<div class='load'><img src='img/loading_dots.gif' class='loader'></div>");
		$(".load").fadeIn(1000);
		console.log($(".load"));
		$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
		
		$.get("../Chatbot/chatbot.php", {q: query}, function(data) {
			data = JSON.parse(data);
			var reply = data["result"];
			$(".load").fadeOut(1000, function() {
				$(".chat-body").append("<div class='reply'>" + reply + "</div>");
				$(".reply").fadeIn(1000);
				$(".chat-body").animate({ scrollTop: $(".chat-body")[0].scrollHeight}, 1000);
			});
		})
	}
}