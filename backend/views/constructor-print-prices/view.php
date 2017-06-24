<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorPrintPrices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Print Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-print-prices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Вернуться', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'type_id',
                'label' => 'Тип печати',
                'value' => function ($data) {
                    return $data->type->name;
                }
            ],
            [
                'attribute' => 'material_id',
                'label' => 'Материал товара',
                'value' => function ($data) {
                    return $data->material->name;
                }
            ],
            [
                'attribute' => 'size_id',
                'label' => 'Размер принта',
                'value' => function ($data) {
                    return $data->size->name;
                }
            ],
            [
                'attribute' => 'price',
                'label' => 'Розничная цена',
                'value' => function ($data) {
                    return $data->price . ' руб.';
                }
            ],
            [
                'attribute' => 'gross_price',
                'label' => 'Оптовые цены',
                'format' => 'html',
                'value' => function ($data) {
                    $html = '
                        <table class="table table-hover table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>От</th>
                                    <th>До</th>
                                    <th>Цена</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    $prices = json_decode($data->gross_price, true);
                    for ($i = 0; $i < count($prices); $i++) {
                        $html .= '<tr>';
                        $html.= '<td>' . $prices[$i]['from'] .' шт.</td>';
                        $html.= '<td>' . $prices[$i]['to'] .' шт.</td>';
                        $html.= '<td>' . $prices[$i]['price'] .' руб.</td>';
                        $html .= '</tr>';
                    }
                    $html .= '
                            </tbody>
                        </table>
                    ';
                    return $html;
                }
            ],
            [
                'attribute' => 'min_count',
                'label' => 'Минимальное количество товара',
            ],
            [
                'attribute' => 'color',
                'label' => 'Цветность',
            ],
            [
                'label' => 'Дополнительные услуги',
                'format' => 'html',
                'value' => function ($data) {
                    $html = '
                        <table class="table table-hover table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Процент</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    $attendances = $data->attendances;
                    for ($i = 0; $i < count($attendances); $i++) {
                        $html .= '<tr>';
                        $html.= '<td>' . $attendances[$i]->name .'</td>';
                        $html.= '<td>' . $attendances[$i]->percent .'%</td>';
                        $html .= '</tr>';
                    }
                    $html .= '
                            </tbody>
                        </table>
                    ';
                    return $html;
                }
            ],
        ],
    ]) ?>

</div>
