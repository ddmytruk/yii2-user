<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \ddmytruk\user\abstracts\SignInFormAbstract $model
 */

$this->title = 'Sign in';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-4 col-md-offset-4">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur' => false,
            'validateOnType' => false,
            'validateOnChange' => false,
        ]) ?>

        <?= $form->field($model, 'login') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= Html::submitButton('Sign up', ['class' => 'btn btn-success btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>


