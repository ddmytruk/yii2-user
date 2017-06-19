<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \ddmytruk\user\abstracts\SignInFormAbstract $model
 * @var \ddmytruk\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
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

        <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-success btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="col-md-4 col-md-offset-4">
        <?php if($module->enableConfirmationEmail): ?>
            <p class="text-center">
                <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/security/resend']) ?>
            </p>
        <?php endif; ?>
    </div>

</div>


