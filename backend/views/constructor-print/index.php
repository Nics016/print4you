
<?php
$this->title = 'Параметры печати';

$css_file_name = Yii::getAlias('@backend') . '/web/css/constructor-print-options.css';
$this->registerCssFile('/css/constructor-print-options.css?v='. @filemtime($css_file_name));

$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-print-options.js';
$this->registerJsFile('/js/constructor-print-options.js?v=' . @filemtime($js_file_name), [
    'position' => \yii\web\View::POS_END,
    'depends' => [
        'yii\web\JqueryAsset',
    ],
]);

?>


<?= $this->render('constructor-print-sizes', [
	'print_sizes' => $print_sizes
]) ?>

<?= $this->render('constructor-print-types', [
	'print_types' => $print_types
]) ?>

<?= $this->render('constructor-print-attendances', [
	'attendances' => $attendances
]) ?>