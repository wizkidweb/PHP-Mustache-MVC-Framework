$.ajax({
	method: "POST",
	url: "/portfolio",
	dataType: "json"
}).done(function(data) {
	console.log(data);
}).error(function (jqXHR, textStatus, errorThrown) {
	console.error(jqXHR);
});