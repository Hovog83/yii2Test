<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Workshops */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Workshops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="workshops-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
        ],
    ]) ?>

    <hr>

    <h2>Students</h2>
    <?=
	/** @var yii\data\ActiveDataProvider $dataProvider */

	GridView::widget([
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
			[
				'attribute' => 'is send mail',
				'value' => static function ($model) {
                    if ($model->is_send_mail == 0) {
                      return 'mail is not sent';
                    }
					return 'mail is sent';
				}
			],
			[
                'class' => ActionColumn::class,
				'template' => '{delete}',
				'controller' => 'user-workshops', //свойство с именем используемого контроллера
			],

        ],
	]); ?>

</div>
