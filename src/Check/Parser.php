<?php

namespace KarlValentin\LaravelI18nCheck\Check;

use KarlValentin\LaravelI18nCheck\Exceptions\InvalidResourceFileException;
use Symfony\Component\Finder\Finder;

/**
 * Parses language resources.
 *
 * @author Karl Valentin <karl.valentin@kvis.de>
 */
class Parser
{
    /**
     * Parses language resources.
     *
     * @param string $resourcePath
     * @param array $skipDirectories
     *
     * @return array
     */
    public static function parseLanguageResources(
        string $resourcePath,
        array $skipDirectories
    ): array {
        $languages = [];

        $finder = new Finder();

        $finder
            ->depth('== 0')
            ->directories()
            ->in($resourcePath);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                if (in_array($file->getFilename(), $skipDirectories)) {
                    continue;
                }

                $languages[$file->getFilename()] = self::parseDir($file->getRealPath());
            }
        }

        return $languages;
    }

    /**
     * Parses language resource files in path.
     *
     * @param string $path
     *
     * @return array
     */
    private static function parseDir(string $path): array
    {
        $translations = [];

        $finder = new Finder();

        $finder
            ->depth('== 0')
            ->files()
            ->in($path);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $key = str_replace('.php', '', $file->getBasename());

                $resource = include $file->getRealPath();

                if(!is_array($resource)) {
                    throw new InvalidResourceFileException(
                        'Invalid resource file: ' . $file->getBasename()
                    );
                }

                $translations[$key] = $resource;
            }
        }

        return array_dot($translations);
    }
}
