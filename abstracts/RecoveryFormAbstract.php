<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 20.06.17
 * Time: 14:10
 */

namespace ddmytruk\user\abstracts;

use yii\base\Model;
use ddmytruk\user\interfaces\FormInterface;
use ddmytruk\user\Mailer;
use ddmytruk\user\Finder;

abstract class RecoveryFormAbstract extends Model implements FormInterface {

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param Mailer $mailer
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Mailer $mailer, Finder $finder, $config = [])
    {
        $this->mailer = $mailer;
        $this->finder = $finder;
        parent::__construct($config);
    }

    public function getViewPath() {
        return false;
    }

}