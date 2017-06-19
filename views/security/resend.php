<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var ddmytruk\user\abstracts\ResendFormAbstract $model
 */

$this->title = 'Request new confirmation message';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-4 col-md-offset-4">

        <?php $form = ActiveForm::begin([
            'id' => 'resend-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <?= Html::submitButton('Continue', ['class' => 'btn btn-primary btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
