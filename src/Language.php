<?php
/**
 * Copyright (c) 2016  Andrey Yaresko.
 */

/**
 * Created by PhpStorm.
 * User: igorianru
 * Date: 28.09.16
 * Time: 17:34
 *
 * @author Andrey Yaresko <igorianru@gmail.com>
 */

namespace Igorianru\language;

use Illuminate\Support\Facades\App;

/**
 * Class Language.
 *
 * Controls the language version of the application.
 *
 * @package igorianru\language
 */
class Language
{

    /**
     * Set current locale.
     *
     * If `$locale` is not specified or `$locale` does not exists - sets locale to default.
     * Will set application locale automatically.
     *
     * @param null $locale
     */
    public function setLocale($locale = null)
    {
        if (!array_key_exists($locale, config('app.locales')) || !$locale) {
            $locale = config('app.fallback_locale');
        }
        App::setLocale($locale);
    }

    /**
     * Get current locale.
     *
     * @return mixed
     */
    public function getLocale()
    {
        $this->setLocale(request()->segment(1));
        return App::getLocale();
    }

    /**
     * Render a dropdown list of available languages.
     *
     * For each locale code uses current route and adds locale code as prefix.
     * Label content of language item can be configured via `$template_label`.
     * @see Language::formatLabel() for more details.
     *
     * @param string $label_template
     * @return mixed
     */
    public function renderDropdownList($label_template = '{code}')
    {
        $segments = request()->segments();
        $locales = config('app.locales');
        $items = [];
        foreach ($locales as $code => $visible_name) {
            $segments[0] = $code;
            $items[] = collect([
                'url' => url(implode('/', $segments)),
                'label' => $this->formatLabel($label_template, $code, $visible_name)
            ]);
        }
        return view('language::dropdown', ['current' => App::getLocale(), 'items' => $items]);
    }

    /**
     * Performs formatting of label content.
     *
     * `$value` cab be specified as a string or as an array.
     * In the last case, each item of `$value` array will be inspected to perform replacement.
     * For example, if `$template` looks like this '{code} {visible_name} {description}':
     * * it will search item with index 'description' in `$value` array
     * * if that item is found - performs a replacement.
     * That way you can customize label content for language items.
     *
     * @param $template
     * @param $code
     * @param $value
     * @return mixed
     */
    protected function formatLabel($template, $code, $value)
    {
        if (!is_array($value)) {
            $value = ['visible_name' => $value];
        }
        $value = collect($value);
        $value->prepend($code, 'code');
        $content = preg_replace_callback(
            '~{(\w+)}~',
            function ($matches) use ($value) {
                $counter = count($matches);
                if ($counter) {
                    $content = $value->get(next($matches));
                    return $content === false ? $matches : $content;
                }
                return null;
            },
            $template
        );
        return $content;
    }
}