<?php

namespace App\WidgetHelper;

use App\Models\Indicator;

class IndicatorChartValues
{
    public static function target(string|int|null $id): array
    {
        if ($id === null) {
            return [];
        }
        $values = [];
        Indicator::where([
            'project_id' => $id,
        ])->get('target')->each(function ($item) use (&$values) {
            $values[] = $item->target;
        });

        return $values;
    }

    public static function targetByPercentage(string|int|null $id): array
    {
        if ($id === null) {
            return [];
        }
        $values = [];
        $indicator = Indicator::where([
            'project_id' => $id,
        ])->get('target');

        $total = $indicator->sum('target');

        $indicator->each(function ($item) use (&$values, $total) {
            $values[] = $item->target / $total;
        });

        return $values;
    }

    public static function reach(string|int|null $id): array
    {
        if ($id === null) {
            return [];
        }
        $values = [];
        Indicator::where([
            'project_id' => $id,
        ])->get('reached')->each(function ($item) use (&$values) {
            $values[] = $item->reached;
        });

        return $values;
    }

    public static function indicator(string|int|null $id): array
    {
        if ($id === null) {
            return [];
        }
        $values = [];
        Indicator::where([
            'project_id' => $id,
        ])->get('code')->each(function ($item) use (&$values) {
            $values[] = $item->code;
        });

        return $values;
    }
}
