<?php

namespace App\Services;

use Carbon\Carbon;

class DateTimeService
{
    public function formatCompleteDateTime($complete_by_date = '', $complete_by_time = ''): String
    {
        if (!is_null($complete_by_date)) {
            $complete_by_date = Carbon::create($complete_by_date)->format('M j');
        }

        if (!is_null($complete_by_time)) {
            $complete_by_time = Carbon::create($complete_by_time)->format('h:ia');
        }

        $dateFormat = $complete_by_date . '' . $complete_by_time;

        if (!empty($complete_by_date) && !empty($complete_by_time)) {
            $dateFormat = $complete_by_date . ', ' . $complete_by_time;
        }

        return $dateFormat;
    }
}
