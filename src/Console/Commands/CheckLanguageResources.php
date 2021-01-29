<?php

namespace KarlValentin\LaravelI18nCheck\Console\Commands;

use Illuminate\Console\Command;
use KarlValentin\LaravelI18nCheck\Check\LanguageResourcesChecker;

/**
 * Check whether language resources are complete.
 *
 * @author Karl Valentin <karl.valentin@kvis.de>
 */
class CheckLanguageResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:check
                            {--s|skip-directories= : directories that should not be parsed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check language resources.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $checker = new LanguageResourcesChecker(
            resource_path('lang'),
            $this->getDirectoriesToSkip()
        );

        foreach ($checker->getLanguages() as $language) {
            if ($checker->languageResourcesComplete($language)) {
                $this->info('Translations for "' . $language . '" are complete.');
            } else {
                $this->error('Translations for "' . $language . '" are incomplete.');

                foreach ($checker->getMissingTranslations($language) as $key) {
                    $this->line('"' . $key . '" is missing.');
                }
            }
        }

        return $checker->allLanguageResourcesAreComplete() ? 0 : 1;
    }

    /**
     * Get directories that should not be parsed.
     *
     * @return array
     */
    private function getDirectoriesToSkip(): array
    {
        return array_merge(
            config('i18ncheck.skipDirectories'),
            explode(',', $this->option('skip-directories'))
        );
    }
}
