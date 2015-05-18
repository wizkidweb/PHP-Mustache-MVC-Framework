var Functions = {
	init: function() {
		var f = this;
		this.php_ajax("getLang", function(data) {
			f.lang = data;
		});
	},
	php_ajax: function(action, data, callback) {
		if ($.isFunction(data)) {
			callback = data;
		}
		data = data || {};
		callback = callback || function(d){
			console.log(d);
		};
		var ajaxdata = {};
		ajaxdata.action = action;
		$.each(data, function(index,value) {
			ajaxdata[index] = value;
		});
		$.ajax({
			url: window.location.href,
			type: "POST",
			data: ajaxdata,
			dataType: "json"
		}).done(function(data) {
			callback(data);
		}).error(function(jqXHR, textStatus, errorThrown) {
			console.error(jqXHR.responseText, jqXHR);
		});
	}
};

Functions.init();