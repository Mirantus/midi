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