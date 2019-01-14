(function ($){
	$(document).ready(function() {
		$('.cart table.table > tbody > tr > td').each(function() {
			var headers = $(this).attr('headers'),
					title = $('th#'+headers).text();
			$(this).attr('data-title', title);
		});
	});
})(jQuery);
