<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model backend\models\StockRequests */
/* @var $form yii\widgets\ActiveForm */
/* @var $mapOffices */
/* @var $mapStockColors */
/* @var $mapGoods */
/* @var $modelsColors */
/* @var $modelsGoods */

?>

<div class="stock-requests-form">

    <?php $form = ActiveForm::begin(['id' => 'stock-request-create-form']); ?>
	<?= $form->field($model, 'office_id')->dropDownList($mapOffices) ?>

	<!-- COLORS -->
	<?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 50, // the maximum times, an element can be cloned (default 999)
                'min' => 0, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelsColors[0],
                'formId' => 'stock-request-create-form',
                'formFields' => [
                    'color_id',
                    'office_id',
                    'liters',
                ],
            ]); ?>
        <div class="panel panel-default">
        	<div class="panel-heading">
				<h4>
					<i class="glyphicon glyphicon-tint"></i> Краски
					<button type="button" class="add-item btn btn-success btn-sm pull-right">
						<i class="glyphicon glyphicon-plus"></i>Добавить
					</button>
				</h4>
			</div>
			<div class="panel-body">
				<div class="container-items">
		        	<?php foreach ($modelsColors as $i => $modelColor): ?>
						<div class="item panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">Краска</h3>
								<div class="pull-right">
									<button type="button" class="remove-item btn btn-danger btn-xs">
										<i class="glypicon glypicon-minus"></i>
									</button>
								</div>
							</div>
							<div class="panel-body">
								<?= $form->field($modelColor, "[{$i}]color_id")->dropDownList($mapStockColors)->label("Цвет") ?>
								<?= $form->field($modelColor, "[{$i}]liters")->textInput(["maxlength" => true]) ?>
							</div> 
						</div> <!-- item -->
		        	<?php endforeach; ?>
		        </div> 
			</div>
        </div> <!-- panel -->
    <?php DynamicFormWidget::end(); ?>
    <!-- END OF COLORS -->

    <!-- GOODS -->
	<?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper_goods', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items-goods', // required: css class selector
                'widgetItem' => '.item-goods', // required: css class
                'limit' => 50, // the maximum times, an element can be cloned (default 999)
                'min' => 0, // 0 or 1 (default 1)
                'insertButton' => '.add-item-goods', // css class
                'deleteButton' => '.remove-item-goods', // css class
                'model' => $modelsColors[0],
                'formId' => 'stock-request-create-form',
                'formFields' => [
                    'color_id',
                    'office_id',
                    'liters',
                ],
            ]); ?>
        <div class="panel panel-default">
        	<div class="panel-heading">
				<h4>
					<i class="glyphicon glyphicon-th-large"></i> Товары
					<button type="button" class="add-item-goods btn btn-success btn-sm pull-right">
						<i class="glyphicon glyphicon-plus"></i>Добавить
					</button>
				</h4>
			</div>
			<div class="panel-body">
				<div class="container-items-goods">
		        	<?php foreach ($modelsGoods as $i => $modelGood): ?>
						<div class="item-goods panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left">Товар</h3>
								<div class="pull-right">
									<button type="button" class="remove-item-goods btn btn-danger btn-xs">
										<i class="glypicon glypicon-minus"></i>
									</button>
								</div>
							</div>
							<div class="panel-body">
								<?= $form->field($modelGood, "[{$i}]constructor_storage_id")->dropDownList($mapGoods)->label("Товар") ?>
								<?= $form->field($modelGood, "[{$i}]count")->textInput(["maxlength" => true]) ?>
							</div> 
						</div> <!-- item -->
		        	<?php endforeach; ?>
		        </div> 
			</div>
        </div> <!-- panel -->
    <?php DynamicFormWidget::end(); ?>
    <!-- END OF GOODS -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
