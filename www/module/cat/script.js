$(function() {
	$('a[data-confirm="item-del"]').click(function() {
		if (!confirm('Вы действительно хотите удалить?')) return false;
		var link = $(this);
		if (link.attr('data-request') == 'ajax') {
			$.ajax({
				type: 'get',
				url: $(this).attr('href'),
				data: {},
				success: function (data) {
					link.parent('li').hide();
				}
			});
			return false;
		}
		return true;
	});
});