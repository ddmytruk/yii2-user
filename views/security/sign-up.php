<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \ddmytruk\user\abstracts\SignUpFormAbstract $model
 */

$this->title = 'Sign up';
$this->params['breadcrumbs'][] = $this->title;


//var_dump($model->rules());
//var_dump($model);

?>

<?php $form = ActiveForm::begin([
    'id' => 'registration-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'username') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= Html::submitButton('Sign up', ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end(); ?>
