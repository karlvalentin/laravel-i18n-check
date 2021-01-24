# Laravel I18N Check

This package provides the artisan command `i18n:check`. 

It checks that all translations are available in every language.

## Installation

This package can be installed by using composer:

    composer require karlvalentin/laravel-i18n-check

### Optional: Configuration

#### Publish Configuration

For changing the configuration it needs to be published:

    artisan vendor:publish --provider="KarlValentin\LaravelI18nCheck\I18nCheckServiceProvider" --tag="config"

The configuration for this package can now be found in `/config/i18ncheck.php`.

#### Options

##### `array` skipDirectories

The option `skipDirectories` is an array of directories that should not be parsed.

Default definition:

    'skipDirectories' => [
        'vendor',
    ],

## Usage

### Run Artisan Command

To check your language resources please run

    artisan i18n:check

### Run `i18n:check` in a Test

You may want to run the artisan command in a test.

    <?php
    
    use Illuminate\Foundation\Testing\TestCase;
    use Tests\CreatesApplication;
    
    class LanguageResourcesTest extends TestCase
    {
        use CreatesApplication;
    
        public function testTranslationsAreAvailableInAllLanguages()
        {
            $this
                ->artisan('i18n:check')
                ->assertExitCode(0);
        }
    }
