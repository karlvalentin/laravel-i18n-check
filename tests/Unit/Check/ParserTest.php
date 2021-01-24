<?php

use KarlValentin\LaravelI18nCheck\Check\Parser;
use KarlValentin\LaravelI18nCheck\Exceptions\InvalidResourceFileException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    const KEY_BAR_FOO = 'bar.foo';
    const KEY_FOO_BAR = 'foo.bar';

    public function testParseLanguageResources()
    {
        $lang = Parser::parseLanguageResources(
            realpath(__DIR__ . '/../../resources-complete/lang'),
            []
        );

        $this->assertArrayHasKey('de', $lang);
        $this->assertArrayHasKey('en', $lang);
        $this->assertArrayHasKey('vendor', $lang);


        $this->assertArrayHasKey(self::KEY_BAR_FOO, $lang['de']);
        $this->assertArrayHasKey(self::KEY_FOO_BAR, $lang['de']);

        $this->assertArrayHasKey(self::KEY_BAR_FOO, $lang['en']);
        $this->assertArrayHasKey(self::KEY_FOO_BAR, $lang['en']);

        $this->assertArrayHasKey('ignore.foo', $lang['vendor']);
    }

    public function testParseLanguageResourcesSkipsDirectories()
    {
        $lang = Parser::parseLanguageResources(
            realpath(__DIR__ . '/../../resources-complete/lang'),
            [
                'vendor',
            ]
        );

        $this->assertArrayHasKey('de', $lang);
        $this->assertArrayHasKey('en', $lang);
        $this->assertArrayNotHasKey('vendor', $lang);

        $this->assertArrayHasKey(self::KEY_BAR_FOO, $lang['de']);
        $this->assertArrayHasKey(self::KEY_FOO_BAR, $lang['de']);

        $this->assertArrayHasKey(self::KEY_BAR_FOO, $lang['en']);
        $this->assertArrayHasKey(self::KEY_FOO_BAR, $lang['en']);
    }

    public function testParseInvalidLanguageResources()
    {
        $this->expectException(InvalidResourceFileException::class);

        Parser::parseLanguageResources(
            realpath(__DIR__ . '/../../resources-invalid/lang'),
            [
                'vendor',
            ]
        );
    }
}
