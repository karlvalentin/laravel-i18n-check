<?php

namespace KarlValentin\LaravelI18nCheck\Check;

/**
 * Language resources checker.
 *
 * @author Karl Valentin <karl.valentin@kvis.de>
 */
class LanguageResourcesChecker
{
    private array $languagesResources;

    private array $translationKeys = [];

    /**
     * LanguageResourcesChecker constructor.
     *
     * @param string $resourcePath
     * @param array $skipDirectories
     */
    public function __construct(string $resourcePath, array $skipDirectories)
    {
        $this->languagesResources = Parser::parseLanguageResources(
            $resourcePath,
            $skipDirectories
        );

        $this->collectTranslationKeys();
    }

    /**
     * Get languages.
     *
     * @return array
     */
    public function getLanguages(): array
    {
        return array_keys($this->languagesResources);
    }

    /**
     * Check whether all translations are available for a language.
     *
     * @param string $language
     *
     * @return bool
     */
    public function languageResourcesComplete(string $language): bool
    {
        return count($this->getMissingTranslations($language)) === 0;
    }

    /**
     * Get missing translations by language.
     *
     * @param string $language
     *
     * @return array
     */
    public function getMissingTranslations(string $language): array
    {
        $missingTranslations = array_diff(
            $this->translationKeys,
            array_keys($this->languagesResources[$language])
        );

        sort($missingTranslations);

        return $missingTranslations;
    }

    /**
     * Collects all translation keys.
     */
    private function collectTranslationKeys(): void
    {
        foreach ($this->languagesResources as $resources) {
            $this->translationKeys = array_merge(
                $this->translationKeys,
                array_keys($resources)
            );
        }

        $this->translationKeys = array_unique($this->translationKeys);
    }

    /**
     * Check whether all language resources are complete.
     *
     * @return bool
     */
    public function allLanguageResourcesAreComplete(): bool
    {
        foreach ($this->getLanguages() as $language) {
            if ($this->languageResourcesComplete($language) !== true) {
                return false;
            }
        }
        return true;
    }
}
