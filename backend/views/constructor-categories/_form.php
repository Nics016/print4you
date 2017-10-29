<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="constructor-categories-form">
	
	<?php 
    if (!$model->isNewRecord):
        $image = $model::getImagesLink() . '/' . $model->img;
    ?>
        <img src="<?= $image ?>" width="320" alt="">
        <br>
        <br>
    <?php endif;?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->label('Имя')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'alias')->label('Алиас')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'description')->label('Описание')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'inline' => false, //по умолчанию false
        ],
    ]); ?>

    <?= $form->field($model, 'imageFile')->label('Картинка')->fileInput() ?>

    <?= $form->field($model, 'seo_title')->label('SEO Title')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'seo_description')->label('SEO Description')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'seo_keywords')->label('SEO Keywords')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
    
    <?= $form->field($model, 'h1_tag_title')->label('Тег h1 на странице конструктора')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'img_alt')->label('Alt картинки')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'menu_title')->label('Название пункта меню')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
