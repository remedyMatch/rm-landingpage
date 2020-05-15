<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\DynamicTerm;
use App\Repository\DynamicTermRepository;

class DynamicTermsReplacer
{
    private $dynamicTermRepository;

    public function __construct(DynamicTermRepository $dynamicTermRepository)
    {
        $this->dynamicTermRepository = $dynamicTermRepository;
    }

    public function replaceDynamicTerms(string $message): string
    {
        $placeholders = $this->extractPlaceholders($message);

        $dynamicTerms = $this->dynamicTermRepository->findByPlaceholders(array_map(
            static function (string $placeholder) {
                return trim($placeholder, '_');
            },
            $placeholders
        ));

        foreach ($dynamicTerms as $dynamicTerm) {
            /** @var DynamicTerm $dynamicTerm */
            $message = str_ireplace(
                sprintf('__%s__', $dynamicTerm->getPlaceholder()),
                $dynamicTerm->getValue(),
                $message
            );
        }

        return $message;
    }

    private function extractPlaceholders(string $message): array
    {
        preg_match_all('/(?P<placeholder>__[_-a-zA-Z0-9]+__)/', $message, $matches);

        return $matches['placeholder'];
    }
}
