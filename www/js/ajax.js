/**
 * Post ajax form
 * @param {object} $form JQuery form
 */
function sendAjaxForm($form) {
	$.ajax({
		type: $form.attr('method'),
		url: $form.attr('action'),
		data: new FormData($form[0]),
        processData: false,
        contentType: false,
		success: function (data) {
            var offset = $form.offset(),
                result = data.result || '<p>Спасибо, ваши данные приняты.</p>';
            if (data.redirect) window.location = data.redirect;
            if (data.errors) {
				$.each(data.errors, function(field, error) {
					$form.find('#' + field).next('.alert').html(error);
				});
            } else {
				$form.after(result);
				$form.hide();
            }
            window.scrollTo(offset.left, offset.top);
		}
	});
}

$(function() {
	$(document).ajaxStart(function() {
		$('<div/>', {id: 'loading', class: 'loading', text: 'Пожалуйста подождите...'}).appendTo('body');
	});
	$(document).ajaxComplete(function(event,request,settings) {
		$('#loading').remove();
	});
});

$(function() {
    $('form[data-request="ajax"]').submit(function() {
        sendAjaxForm($(this));
        return false;
    });
});

// Sortable rows
$(function() {
    var $sorted = $('.sorted');

    $sorted.sortable({
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        onDrop: function (item, container, _super) {
            var data = [];
            var level = $sorted.data('level') ? '_' + $sorted.data('level') : '';
            var url = '/' + $sorted.data('module') + '/reorder' + level + '/';

            $sorted.find('tr').each(function () {
                data.push($(this).data('id'));
            });

            $.post(url, {data: JSON.stringify(data)});
            _super(item, container);
        }
    });
});