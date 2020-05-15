<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\DynamicTermsReplacer;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class DynamicTermTranslationExtension extends AbstractExtension
{
    private $translationExtension;
    private $dynamicTermsReplacer;

    public function __construct(
        TranslationExtension $translationExtension,
        DynamicTermsReplacer $dynamicTermsReplacer
    ) {
        $this->translationExtension = $translationExtension;
        $this->dynamicTermsReplacer = $dynamicTermsReplacer;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('trans_with_dynamic_term', [$this, 'transWithDynamicTerm']),
        ];
    }

    public function transWithDynamicTerm(string $message, array $arguments = [], string $domain = null, string $locale = null, int $count = null): string
    {
        return $this->dynamicTermsReplacer->replaceDynamicTerms(
            $this->translationExtension->trans($message, $arguments, $domain, $locale, $count)
        );
    }
}
