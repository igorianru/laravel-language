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

namespace igorianru\language;

use Illuminate\Support\Facades\Facade;

/**
 * @see igorianru\laravel\Language
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