<?php

use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Workshops';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-workshops-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Workshops', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],
              [
                'label' => 'Workshops',
                'attribute' => 'workshops_id',
                'value' => "workshops.name"
              ],
              [
                'attribute' => 'User',
                'value' => "user.email"
              ],

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
