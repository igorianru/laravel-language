# Laravel language
Multilingual routes and language switcher for laravel 5

# Installation
The preferred way to install extension is via composer. Check the composer.json for this extension's requirements and dependencies.

To install, either run
```php
$ php composer.phar require igorianru/laravel-language "*"
```
or add
```php
"igorianru/laravel-language": "*"
```
to the require section of your composer.json.

# Configuration
After installing the Socialite library, register the igorianru\language\LanguageServiceProvider in your <code>config/app.php</code> configuration file:
```php
'providers' => [
    // Other service providers...
    igorianru\language\LanguageServiceProvider::class,
],
```
Also, add the Language facade to the aliases array in your app configuration file:
```php
'Language' => igorianru\language\LanguageFacade::class,
```

# Usage
Use <code>Language::getLocale()</code> method to add language prefix to your routes:
```php
Route::group(['prefix' => Language::getLocale()], function () { 
    Route::get('/home', function () {
        return view('frontend.index');
    })->name('home');
});
```
Use <code>Language::renderDropdownList()</code> in your view-file to generate language dropdown list (note a exclamation marks):
```php
{!! Language::renderDropdownList() !!}
```
This method takes 'locales' array which specified in you <code>app.config</code> file. It assumes that each 'locale' item key is a language 'code' and value is a 'visible_name'. 
You can control language item label content via <code>Language::renderDropdownList()</code> $label_template value:
```php
{!! Language::renderDropdownList('{visible_name} ({code})') !!}
```
Label will look like this: <code>'English (en)'</code>
You can add additional items to any 'locale' item value and render that value in <code>Language::renderDropdownList()</code>.
For example, if you added 'description' and your <code>app.config</code> looks something like this:
```php
'locales' => [
    'en' => [
        'visible_name' => 'English',
        'description' => 'Some simple text'
    ], 
    'ru' => 'Русский',
]
```
You can render 'description' item:
```php
{!! Language::renderDropdownList('{visible_name} ({code}) {description}') !!}
```
Label will look like this: <code>'English (en) Some simple text'</code>

# Customization
You can customize html of language dropdown list.
Simply run <code>php ./artisan vendor:publish --tag=language</code> and edit <code>dropdown.blade.php</code> template in your <code>resources/views/vendor/language</code> directory.