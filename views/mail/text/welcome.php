<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\web\View $this
 * @var \ddmytruk\user\models\orm\Token $token
 * @var \ddmytruk\user\abstracts\SignUpFormAbstract $model
 */
?>
<?= Yii::t('user', 'Hello') ?>,

<?= Yii::t('user', 'Your account on {0} has been created', Yii::$app->name) ?>.

<?php if ($token !== null): ?>
    <?= Yii::t('user', 'In order to complete your registration, please click the link below') ?>.

    <?= $token->url ?>

    <?= Yii::t('user', 'If you cannot click the link, please try pasting the text into your browser') ?>.
<?php endif ?>

<?= Yii::t('user', 'If you did not make this request you can ignore this email') ?>.
