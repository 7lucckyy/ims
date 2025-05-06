<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EvaluationCriteria: string implements HasLabel
{
    case CommunicationSkills = 'communication_skills';
    case ProblemSolving = 'problem_solving';
    case Productivity = 'productivity';
    case QualityOfWork = 'quality_of_work';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CommunicationSkills => 'Communication Skills',
            self::ProblemSolving => 'Problem Solving',
            self::Productivity => 'Productivity',
            self::QualityOfWork => 'Quality of Work',
        };
    }
}
