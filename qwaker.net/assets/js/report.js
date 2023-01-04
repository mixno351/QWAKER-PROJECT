function goReport(type, content) {
	showProgressBar();
	$.ajax({type: "GET", url: "/assets/alert/view-report.php", data: {type: type, content: content}, success: function(result) {
			hideProgressBar();
			$("body").append(result);
		}
	});
}