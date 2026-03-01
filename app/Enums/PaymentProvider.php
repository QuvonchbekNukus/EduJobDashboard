<?php

namespace App\Enums;

enum PaymentProvider: string
{
    case CLICK = 'click';
    case PAYME = 'payme';
    case P2P = 'p2p';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $case): string => $case->value,
            self::cases()
        );
    }
}
