<?php
use common\models\OrdersProduct;
use common\models\ConstructorPrintTypes;

$products = OrdersProduct::find()->where(['order_id' => $model->id])->asArray()->orderBy('id')->all();
$types = ConstructorPrintTypes::find()->asArray()->all();
?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Все товары</h4>
            </div>

            <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Название</td>
                        <td>Данные</td>
                        <td>Размер</td>
                        <td>Количество</td>
                        <td>Скидка</td>
                        <td>Цена товара</td>
                        <td>Итоговая цена</td>
                    </tr>
                </thead>
        
                <tbody>
                <?php
                for ($i = 0; $i < count($products); $i++) {
                    if ($products[$i]['is_constructor'])
                        echo $this->render('constructor-product-row', ['product' => $products[$i], 'types' => $types]);
                } 
                ?>
                </tbody>

            </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
