<?php
/**
 * Copyright (c) 2016  Andrey Yaresko.
 */

/**
 * Created by PhpStorm.
 * User: igorianru
 * Date: 29.09.16
 * Time: 7:04
 *
 * @author Andrey Yaresko <igorianru@gmail.com>
 */

namespace Igorianru\language;

use Illuminate\Support\Facades\Facade;

/**
 * @see Igorianru\laravel\Language
 */
class LanguageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'language';
    }
}