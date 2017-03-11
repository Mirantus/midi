// Sortable rows
$(function() {
    var $sorted = $('.sorted');

    $sorted.sortable({
        containerSelector: '.sorted',
        itemPath: '> ul',
        itemSelector: 'li',
        placeholder: '<li class="placeholder"/>',
        onDrop: function (item, container, _super) {
            var data = [];
            var level = $sorted.data('level') ? $sorted.data('level') : '';
            var url = '/' + $sorted.data('module') + '/reorder' + level + '/';

            $sorted.find('li').each(function () {
                data.push($(this).data('id'));
            });

            $.post(url, {data: JSON.stringify(data)});
            _super(item, container);
        }
    });
});