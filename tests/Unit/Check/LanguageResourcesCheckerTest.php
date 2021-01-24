<?php

use KarlValentin\LaravelI18nCheck\Check\LanguageResourcesChecker;
use PHPUnit\Framework\TestCase;

class LanguageResourcesCheckerTest extends TestCase
{
    public function testGetLanguages()
    {
        $checker = new LanguageResourcesChecker(
            realpath(__DIR__ . '/../../resources-complete/lang'),
            [
                'vendor',
            ]
        );

        $languages = $checker->getLanguages();

        $this->assertIsArray($languages);
        $this->assertContains('de', $languages);
        $this->assertContains('en', $languages);
    }

    public function testLanguageResourceComplete()
    {
        $checker = new LanguageResourcesChecker(
            realpath(__DIR__ . '/../../resources-complete/lang'),
            [
                'vendor',
            ]
        );

        $this->assertTrue($checker->languageResourcesComplete('de'));
        $this->assertTrue($checker->languageResourcesComplete('en'));

        $this->assertTrue($checker->allLanguageResourcesAreComplete());
    }

    public function testLanguageResourceIncomplete()
    {
        $checker = new LanguageResourcesChecker(
            realpath(__DIR__ . '/../../resources-incomplete/lang'),
            [
                'vendor',
            ]
        );

        $this->assertTrue($checker->languageResourcesComplete('de'));
        $this->assertFalse($checker->languageResourcesComplete('en'));

        $this->assertFalse($checker->allLanguageResourcesAreComplete());
    }

    public function testGetMissingTranslations()
    {
        $checker = new LanguageResourcesChecker(
            realpath(__DIR__ . '/../../resources-incomplete/lang'),
            [
                'vendor',
            ]
        );

        $missingEnglishTranslations = $checker->getMissingTranslations('en');

        $this->assertContains('bar.extra', $missingEnglishTranslations);
    }
}
