var App = {
	vr : {
		user : {}	
	},
	init : function(){		
	},
	page : {
		resetForm : function(id) {
			$('.long_url').attr('disabled', false).val('').focus();
			$('.short_url').attr('disabled', true).val('');
			$('.action_button').toggleClass('hidden');
			$('.reset_button').toggleClass('hidden');
		},
		error : function(text) {
			$('.error-block').stop().hide().removeClass('hidden').html(text).slideDown().delay(4000).slideUp();
		}
	},
	data : {
		send : {
		},
		get : {
			shortUrl : function(){
				url = $('.long_url').val().trim();
				$('.long_url').val(url)
				if(url.length > 0)
					$.post('/ajax', {action: 'get_short', data: {url: url}}, function(data){
						if(data.error)
							App.page.error(data.error);
						
						shortUrl = App.vr.user.url + data.short;
						$('.short_url').val(shortUrl).attr('disabled', false).focus()
						$('.long_url').attr('disabled', true);
						$('.action_button').toggleClass('hidden');
						$('.reset_button').toggleClass('hidden');
					}).error(function(jqXHR, textStatus, errorThrown){
							App.page.error('Error getting data. Server responsed with message: "' + errorThrown +'"');
					})
				else
					App.page.error('Url is too short');
			}
		}
	}
}

String.prototype.trim = function(){
	return $.trim(this);
}