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
 * @var ddmytruk\user\abstracts\UserAbstract   $user
 * @var ddmytruk\user\models\orm\Token  $token
 */
?>
<?= 'Hello' ?>,

<?= 'Thank you for signing up on ' . Yii::$app->name ?>.
<?= 'In order to complete your registration, please click the link below' ?>.

<?= $token->url ?>

<?= 'If you cannot click the link, please try pasting the text into your browser' ?>.

<?= 'If you did not make this request you can ignore this email' ?>.
