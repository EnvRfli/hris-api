<?php

namespace App\Enums;

class EmploymentStatus
{
    const PERMANENT = 'permanent';
    const CONTRACT = 'contract';
    const INTERNSHIP = 'internship';
    const PROBATION = 'probation';

    /**
     * Get all employment statuses
     */
    public static function all(): array
    {
        return [
            self::PERMANENT,
            self::CONTRACT,
            self::INTERNSHIP,
            self::PROBATION,
        ];
    }

    /**
     * Get employment statuses with labels
     */
    public static function options(): array
    {
        return [
            self::PERMANENT => 'Permanent Employee',
            self::CONTRACT => 'Contract Employee',
            self::INTERNSHIP => 'Internship',
            self::PROBATION => 'Probation Period',
        ];
    }

    /**
     * Get validation rule
     */
    public static function rule(): string
    {
        return 'in:' . implode(',', self::all());
    }

    /**
     * Get label
     */
    public static function label(string $status): string
    {
        return self::options()[$status] ?? $status;
    }
}
