<?php

use yii\helpers\Html;
use yii\grid\GridView;

// common constructor-storage.js
$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-storage.js';
$this->registerJsFile('/js/constructor-storage.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => [
        'yii\bootstrap\BootstrapAsset',
	],
]);

$this->title = 'Цвета товара "' . $product->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>

	.refresh-icon {
		text-align: center;
	    display: block;
	    font-size: 35px;
	    padding: 30px 0;
	    color: #000;
	}

	.glyphicon-refresh-animate {
	    -animation: spin .7s infinite linear;
	    -ms-animation: spin .7s infinite linear;
	    -webkit-animation: spinw .7s infinite linear;
	    -moz-animation: spinm .7s infinite linear;
	}

	@keyframes spin {
	    from { transform: scale(1) rotate(0deg);}
	    to { transform: scale(1) rotate(360deg);}
	}
	  
	@-webkit-keyframes spinw {
	    from { -webkit-transform: rotate(0deg);}
	    to { -webkit-transform: rotate(360deg);}
	}

	@-moz-keyframes spinm {
	    from { -moz-transform: rotate(0deg);}
	    to { -moz-transform: rotate(360deg);}
	}
</style>

<div class="constructor-colors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Имя цвета',
                'contentOptions' => function ($data) {
                	return [
                		'data-attr' => 'name',
                		'data-val' => $data->name,
                	];
                }
            ],
            [
               'attribute' => 'color_value',
               'label' => 'Цвет',
               'format' => 'html', 
               'value' => function ($data) {
                    return 
                        '<div style="border: 1px solid black; width: 50px; height: 20px; 
                            background: ' . $data->color_value .';">
                        </div>';
               }
            ],
            [
            	'attribute' => 'small_front_image',
            	'label' => 'Лицевая сторона',
            	'format' => 'html',
            	'value' => function ($data) {
            		return Html::img($data::getSmallFrontImageLink() . '/' . $data->small_front_image, [
            			'alt' => $data->name,
            			'width' => 100,
            		]);
            	}
            ],
            [
            	'attribute' => 'small_back_image',
            	'label' => 'Обратная сторона',
            	'format' => 'html',
            	'value' => function ($data) {
            		return Html::img($data::getSmallFrontImageLink() . '/' . $data->small_back_image, [
            			'alt' => $data->name,
            			'width' => 100,
            		]);
            	}
            ],	
            [
            	'label' => 'Действие',
            	'format' => 'html',
            	'contentOptions' => function ($data) {
            		return [ 'data-color_id' => $data->id ];
            	},

            	'value' => function ($data) {
            		return Html::a('Изменить', '#', [
            			'class' => 'btn btn-primary open-modal',
            		]);
            	}
            ],
          
        ],
    ]); ?>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>

			<div class="modal-body">
				<div id="modal-loader" style="width:100%; height:100%">
					<span class="glyphicon refresh-icon glyphicon-refresh glyphicon-refresh-animate"></span>
				</div>
				<div id="forms-container"></div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-primary" id="save-changes">Сохранить</button>
			</div>
		</div>
	</div>
</div>
