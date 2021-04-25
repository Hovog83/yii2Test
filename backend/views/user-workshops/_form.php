<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserWorkshops */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-workshops-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
	$form->field($model, 'workshops_id')->widget(Select2::classname(), [
		'showToggleAll' => false,
		'options' => ['placeholder' => 'select workshops','id'=>'workshops_id'],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 2,
			'language' => [
				'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
			],
			'ajax' => [
				'url' => \yii\helpers\Url::to(['get-workshops']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) {return {q:params.term}; }')
			],
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
			'templateResult' => new JsExpression('function(city) { return city.text; }'),
			'templateSelection' => new JsExpression('function (city) { return city.text; }'),
		],
	]) ?>
	<?=
	$form->field($model, 'user_ids[]')->widget(Select2::classname(), [
		'showToggleAll' => false,
		'options' => ['placeholder' => 'select user','multiple' => true],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 2,
			'language' => [
				'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
			],
			'ajax' => [
				'url' => \yii\helpers\Url::to(['get-user']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) {return {q:params.term,workshops_id:document.getElementById("workshops_id").value}; }')
			],
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
			'templateResult' => new JsExpression('function(city) { return city.text; }'),
			'templateSelection' => new JsExpression('function (city) { return city.text; }'),
		],
	]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
