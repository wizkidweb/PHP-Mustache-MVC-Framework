var Functions = {
	lang: "en_us",
	init: function() {
		var f = this;
		// Setup Lang
		$.getJSON("app/lang/"+this.lang+".lang.json", function(json) {
			f.lang = json;
			$(function() {
				f.alert = function(msg) {
					var $a = $("<div/>"),
						$b = $("<button/>");
					$a.addClass('alert').addClass('alert-warning').addClass('alert-dismissable');
					$a.attr('role', 'alert');
					$a.html(msg);

					$b.addClass('close');
					$b.attr('type','button').attr('data-dismiss', 'alert').attr('aria-label', 'Close');
					$b.html('<span aria-hidden="true">&times;</span>');

					$a.prepend($b);
					$("#alerts").append($a);
				};
			});
		});
	}
};