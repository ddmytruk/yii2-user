<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 19.06.17
 * Time: 12:27
 */

namespace ddmytruk\user\abstracts;


use ddmytruk\user\Finder;
use ddmytruk\user\interfaces\FormInterface;
use ddmytruk\user\Mailer;
use yii\base\Model;

abstract class ResendFormAbstract extends Model implements FormInterface
{
    /**
     * @var string
     */
    public $email;

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