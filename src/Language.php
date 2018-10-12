<?php

namespace Igorianru\language;

use Config;
use Cookie;
use Crypt;
use Illuminate\Support\Facades\App;
use Request;

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
   * @var array
   */
  public $locale;

  /**
   * @var mixed
   */
  public $seg;

  public function __construct()
  {
    $cookie_locale = Cookie::get('locale') ? Crypt::decryptString(Cookie::get('locale')) : null;

    if($cookie_locale === null) {
      if(($list = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''))) {
        if(preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)) {
          $this->locale = array_combine($list[1], $list[2]);

          foreach($this->locale as $n => $v)
            $this->locale[$n] = $v ? $v : 1;

          arsort($this->locale, SORT_NUMERIC);
        }
      } else {
        $this->locale = [];
      }

      $this->seg = $this->getBestMatch('ru', ['ru' => ['ru', 'be', 'uk', 'ky', 'ab', 'mo', 'et', 'lv'], 'en' => 'en']);
    }
  }

  /**
   * @param $default
   * @param $langs
   * @return mixed
   */
  public function getBestMatch($default, $langs = ['ru' => ['ru', 'be', 'uk', 'ky', 'ab', 'mo', 'et', 'lv'], 'en' => 'en'])
  {
    $languages = [];

    foreach($langs as $lang => $alias) {
      if(is_array($alias)) {
        foreach($alias as $alias_lang) {
          $languages[strtolower($alias_lang)] = strtolower($lang);
        }

      } else {
        $languages[strtolower($alias)] = strtolower($lang);
      }
    }

    foreach($this->locale ?? [] as $l => $v) {
      $s = strtok($l, '-');

      if(isset($languages[$s]))
        return $languages[$s];
    }

    return $default;
  }

  /**
   * Get current locale.
   *
   * @return mixed
   */
  public function getLocale()
  {
    $seg = $this->seg ?? request()->segment(1);

    if($seg) {
      $this->setLocale($seg);
      Cookie::queue('locale', request()->segment(1), time() + 3600 * 30);
    }

    return App::getLocale();
  }

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
    if(!array_key_exists($locale, config('app.locales')) || !$locale)
      $locale = config('app.fallback_locale');

    App::setLocale($locale);
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
    $locales  = config('app.locales');
    $items    = [];

    foreach($locales as $code => $visible_name) {
      $segments[0] = $code;

      $items[] = collect(
        [
          'url'   => url(implode('/', $segments)),
          'label' => $this->formatLabel($label_template, $code, $visible_name),
        ]
      );
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
    if(!is_array($value))
      $value = ['visible_name' => $value];

    $value = collect($value);
    $value->prepend($code, 'code');

    $content = preg_replace_callback(
      '~{(\w+)}~',

      function($matches) use ($value) {
        $counter = count($matches);

        if($counter) {
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