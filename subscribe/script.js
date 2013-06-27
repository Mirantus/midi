$(function() {
	var $form = $('form[name="subscribe"]');

	$form.submit(function() {
		if ($form.attr('data-request') == 'ajax') {
			sendAjaxForm($form);
			return false;
		}

		return true;
	});
});