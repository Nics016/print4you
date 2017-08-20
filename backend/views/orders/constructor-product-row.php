<?php

use common\models\OrdersProduct;
use common\models\ConstructorSizes;
use common\models\ConstructorPrintTypes;
use common\models\ConstructorPrintSizes;
use common\models\ConstructorPrintAttendance;
use common\models\ConstructorAdditionalSides;

use yii\helpers\Html;

if (!function_exists('renderPrintData')) {
    function renderPrintData ($array, $product, $types, $side_name, $side_id = '') {
        $string = "
            <div class='product-side-data' 
                data-side-name='$side_name' 
                data-side-id='$side_id'>
        ";

        if (!empty($array)) {

            if (isset($array['type_id']) && $array['type_id'] != null) {
                $string .= 'Тип печати - ' ;
                $type = ConstructorPrintTypes::findOne(['id' => $array['type_id']]);
                $type = $type ? $type->name : 'неизвестно';
                $string .= '<span class="current-type">' . $type . '</span>';
                $string .= '</br>';
                $string .= '<select class="type-select form-control">';
                for ($i = 0; $i < count($types); $i++) {
                    $type_id = $types[$i]['id'];
                    $type_name = $types[$i]['name'];
                    $selected = $type_id == $array['type_id'] ? 'selected' : '';
                    $string .= "<option value='$type_id'>$type_name</option>";
                }
                $string .= '</select>';
                $string .= '</br>';
            }

            if (isset($array['size_id']) && $array['size_id'] != null) {
                $string .= 'Размер печати - ' ;
                $size = ConstructorPrintSizes::findOne(['id' => $array['size_id']]);
                $string .= $size ? $size->name: 'неизвестно';
                $string .= '</br>';
            }

            $string .= isset($array['color']) ? 'Цветность - ' . $array['color'] . '</br>' : '';

            if (isset($array['attendance']) && $array['attendance'] != null ) {
                $string .= 'Доп. услуга - ';
                $attendance = ConstructorPrintAttendance::findOne(['id' => $array['attendance']['id']]);
                $string .= $attendance ?   $attendance->name : 'неизвестно';
                $string .= '</br>';
            }

            $string .= isset($array['price']) ? 'Цена принта - ' . $array['price'] . 'Р. </br>' : '';

        }

        $image_array = [];
        $text_array = [];

        for ($i = 0; $i < count($array['data']); $i++)
        {
            if ($array['data'][$i]['type'] == 'image')
                $image_array[] = $array['data'][$i];

            if ($array['data'][$i]['type'] == 'text')
                $text_array[] = $array['data'][$i];
        }

        if (count($image_array) > 0)
            $string .= '<h4>Картинки принта</h4>';

        for ($i = 0; $i < count($image_array); $i++) {
            $string .= Html::a('Ссылка', OrdersProduct::getImagesLink($product['folder_name']) . '/' .$image_array[$i]['filename']);
        }

        if (count($text_array) > 0)
            $string .= '<h4>Тексты принта</h4>';

        for ($i = 0; $i < count($text_array); $i++) {
            if (isset($text_array[$i]['text']))
                $string .= '<p>Текст: ' . $text_array[$i]['text'] .'</p>';

            if (isset($text_array[$i]['font_family']))
                $string .= '<p>Шрифт: ' . $text_array[$i]['font_family'] .'</p>';

            if (isset($text_array[$i]['color']))
                $string .= '<p>Цвет: ' . $text_array[$i]['color'] .'</p>';

            $string .= '</br>';
        }
        $string .= '
            <span class="side-error" style="color: red; font-size: 16px;"></span>
            <br><br>
            <button class="btn btn-success save-product-changes">Сохранить изменения</button>
        </div>';
        return $string;
    }
}

$name = $product['name'];

$front_data = json_decode($product['front_print_data'], true);
$back_data = json_decode($product['back_print_data'], true);

$front_html = renderPrintData($front_data, $product, $types, 'front');
$back_html = renderPrintData($back_data, $product, $types, 'back');

$size = ConstructorSizes::find()->where(['id' => $product['size_id']])->one();
$count = $product['count'];
$discount_percent = $product['discount_percent'];

?>

<tr data-id="<?= $product['id'] ?>">
	<td><?= $name ?></td>
	<td>
		<h3>Лицевая сторона</h3>
		<p>
			Принт: 
			<?= Html::a('Ссылка', OrdersProduct::getImagesLink($product['folder_name']) . '/' .$product['front_image']) ?>
		</p>
		<?= $front_html ?>
		<br>
		<h3>Обратная сторона</h3>
		<p>
			Принт: 
			<?= Html::a('Ссылка', OrdersProduct::getImagesLink($product['folder_name']) . '/' .$product['back_image']) ?>
		</p>
		<?= $back_html ?>

        <?php 

        $additional_price = 0;
        $additional_data = json_decode($product['additional_print_data'], true);
        $additional_html = [];
        ?>

        <?php
        for ($i = 0; $i < count($additional_data); $i++):
            $current = $additional_data[$i];
            $additional_price += $current['price'];
            $html = renderPrintData($current, $product, $types, 'additional', $current['side_id']);
            $side = ConstructorAdditionalSides::findOne($current['side_id']);
            $side_name = $side != null ? $side->name : 'Неизвестная сторона';
        ?>
        
            <h3><?= $side_name ?> </h3>
            <p>
                Принт: 
                <?= Html::a('Ссылка', OrdersProduct::getImagesLink($product['folder_name']) . '/' .$current['image']) ?>
            </p>
            
            <?= $html ?>

        <?php 
        endfor;
        $full_price = $product['price'] + $front_data['price'] + $back_data['price'] + $additional_price;
        $price = $count * $full_price;
        ?>

	</td>
    
	<td><?= $size ? $size->size : 'Неизвестно' ?></td>
	<td><?= $count ?></td>
	<td><?= $discount_percent ?>%</td>
	<td class="product-price"><?= $price ?>Р</td>
	<td>
        <input type="number" class="form-control total-price" min="0"
            value="<?= ceil($price * (100 - $discount_percent) / 100) ?>">   
    </td>
</tr>

