<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AppraisalMethod: string implements HasLabel
{
    case AllRound = 'all_round';
    case SelfAppraisal = 'self_appraisal';
    case PeerReviews = 'PeerReviews';
    case Managerial = 'Managerial';

    public const DEFAULT = self::AllRound;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AllRound => 'All Round',
            self::SelfAppraisal => 'Self Appraisal',
            self::PeerReviews => 'Peer Reviews',
            self::Managerial => 'Managerial',
        };
    }
}
