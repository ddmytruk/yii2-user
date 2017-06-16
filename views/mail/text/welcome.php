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
Hello,

Your account on has been created <?= Yii::$app->name ?>.

    We have generated a password for you

<?php if ($token !== null): ?>
    In order to complete your registration, please click the link below.

    <?= $token->url ?>

    If you cannot click the link, please try pasting the text into your browser.
<?php endif ?>

If you did not make this request you can ignore this email.
