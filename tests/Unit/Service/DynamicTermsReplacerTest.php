<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\DynamicTerm;
use App\Repository\DynamicTermRepository;
use App\Service\DynamicTermsReplacer;
use PHPUnit\Framework\TestCase;

final class DynamicTermsReplacerTest extends TestCase
{
    /**
     * @dataProvider provideMessages
     */
    public function test_replace_placeholders_works_correctly(
        string $messageWithPlaceholder,
        string $expectedMessagesWithReplacedPlaceholder,
        array $dynamicTerms
    ): void {
        $dynamicTermsReplacer = new DynamicTermsReplacer(
            $dynamicTermRepositoryMock = $this->createMock(DynamicTermRepository::class)
        );

        $dynamicTermRepositoryMock
            ->method('findByPlaceholders')
            ->with($this->anything())
            ->willReturn($dynamicTerms)
        ;

        $this->assertSame(
            $expectedMessagesWithReplacedPlaceholder,
            $dynamicTermsReplacer->replaceDynamicTerms($messageWithPlaceholder)
        );
    }

    public function provideMessages(): \Generator
    {
        yield [
            'Personen: __EXPERT_COUNT__ | Partner: __PARTNER__',
            'Personen: __EXPERT_COUNT__ | Partner: __PARTNER__',
            [],
        ];
        yield [
            'Personen: __EXPERT_COUNT__',
            'Personen: 80',
            [
                new DynamicTerm('EXPERT_COUNT', '80'),
            ],
        ];
        yield [
            'Partner: __PARTNER__',
            'Partner: Partner One, Partner Two',
            [
                new DynamicTerm('PARTNER', 'Partner One, Partner Two'),
            ],
        ];
        yield [
            'Personen: __EXPERT_COUNT__ | Partner: __PARTNER__',
            'Personen: 80 | Partner: Partner One, Partner Two',
            [
                new DynamicTerm('EXPERT_COUNT', '80'),
                new DynamicTerm('PARTNER', 'Partner One, Partner Two'),
            ],
        ];
        yield [
            'Personen: __EXPERT_COUNT__ | Partner: __PARTNER__',
            'Personen: 80 | Partner: __PARTNER__',
            [
                new DynamicTerm('EXPERT_COUNT', '80'),
            ],
        ];
    }
}
