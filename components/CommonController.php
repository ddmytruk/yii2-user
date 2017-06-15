<?php
/**
 * Created by PhpStorm.
 * User: dmytrodmytruk
 * Date: 12.06.17
 * Time: 15:20
 */

namespace ddmytruk\user\components;

use ddmytruk\user\DI;
use yii\web\Controller;

use ddmytruk\user\Finder;

class CommonController extends Controller
{

    /**
     * @var DI
     */
    protected $di;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param DI               $di
     * @param array            $config
     */
    public function __construct($id, $module, Finder $finder, DI $di, $config = [])
    {
        $this->finder = $finder;
        $this->di = $di;

        foreach ($config as $name => $definition) {

            if((substr_count($name, 'before') && !substr_count($name, 'on before'))
                || substr_count($name, 'after') && !substr_count($name, 'on after')) {

                $config['on '.$name] = $config[$name];
                unset($config[$name]);
            }
        }

        parent::__construct($id, $module, $config);
    }

}