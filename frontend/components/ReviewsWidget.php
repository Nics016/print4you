<?php
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;
use common\models\Reviews;

class ReviewsWidget extends Widget
{
    public $reviews;

    public function init()
    {
        parent::init();
        if ($this->reviews === null) {
            $this->reviews = $query = Reviews::find()->where(['is_published' => true])->orderBy('id DESC')->asArray()->with('user')->all();
        }
    }

    public function run()
    {
        $html = '';
        if (count($this->reviews) > 0):
        $activeNum = (count($this->reviews) < 3) ? count($this->reviews) : 2;
        $html .= '<div class="line3">
            <div class="container">
                <div class="line3-title">
                    <h3>
                        Что говорят о нас
                    </h3>
                    <div class="line3-title-reviews">
                        <h2>
                            Наши клиенты
                        </h2>
                        <div class="line3-title-reviews-underline"></div>
                    </div>
                </div>
                <div class="line3-carousel">
                    <div class="line3-carousel-portraits clearfix">';
                $i = 1;
                foreach ($this->reviews as $review):
                    $className = "";
                    if ($i >= 4) {
                        $className = "hidden";
                    } elseif ($i === $activeNum) {
                        $className = "active";
                    }
                    $html .= '<img src="/img/lk-circle.png" alt="" class="' . $className . '">';
                    $i++;
                endforeach;
                $html .= '</div>
                    <div class="line3-carousel-info">';
                    $i = 1;
                    foreach ($this->reviews as $review):
                    $className = "";
                    if ($i === $activeNum) {
                        $className = "active";
                    }
                    $html .=   '<section class="' . $className . '">
                            <h4>
                                ' . $review["user"]["firstname"] . '
                            </h4>
                            <p>
                                ' . $review["text"] . '
                            </p>
                        </section>';
                    $i++;
                    endforeach;
                $html .= '        
                    </div>
                    <div class="line3-carousel-circles">
                    </div>
                </div>

                <a href="#" class="line3-leaveReview">Оставить отзыв</a>
            </div>
        </div>
        <!-- END OF LINE3 -->';
        endif;
        return $html;
    }
}