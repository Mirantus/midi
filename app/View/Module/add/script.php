<?php
/**
 * @var app\Controller\ModuleController $this
 */
?>
<script src="/js/jwysiwyg/jquery.wysiwyg.js"></script>
<script src="/js/jwysiwyg/controls/wysiwyg.image.js"></script>
<script src="/js/jwysiwyg/controls/wysiwyg.link.js"></script>
<script src="/js/jwysiwyg/controls/wysiwyg.table.js"></script>
<script src="/js/wysiwyg.js?<?=$this->app->version;?>"></script>
<script>
    // TODO: add before ajax validation
//    $(function() {
//        var $form = $('form[name="add"]');
//
//        $form.submit(function() {
//            if (!$('#agree').is(':checked')) {
//                alert('Для того, чтобы продолжить, примите лицензионное соглашение.');
//                return false;
//            }
//
//            return true;
//        });
//    });
</script>