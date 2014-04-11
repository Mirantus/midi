$(function() {
	var $form = $('form[name="add"]');

	$form.submit(function() {
		if (!$('#agree').is(':checked')) {
			alert('Для того, чтобы продолжить, примите лицензионное соглашение.');
			return false;
		}

		if ($form.attr('data-request') == 'ajax') {
			alert('asdf');
			//sendAjaxForm($form);
			return false;
		}

		return true;
	});
});