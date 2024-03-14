<?php

namespace App\Helper;

use Carbon\Carbon;

class LineFeeCalculation
{
    private $startDateOfCurrentMonth;
    private $endDateOfCurrentMonth;
    private $startDateOfNextMonth;
    private $endDateOfNextMonth;
    private $numberOfDaysInCurrentMonth;
    private $numberOfDaysInNextMonth;

    public function __construct()
    {
        $now = Carbon::now();

        $this->startDateOfCurrentMonth = (clone $now)->startOfMonth();
        $this->endDateOfCurrentMonth = (clone $now)->endOfMonth();
        $this->startDateOfNextMonth = (clone $this->endDateOfCurrentMonth)->addDays(1);
        $this->endDateOfNextMonth = (clone $this->startDateOfNextMonth)->endOfMonth();

        $this->numberOfDaysInCurrentMonth = $now->daysInMonth;
        $this->numberOfDaysInNextMonth = $this->startDateOfNextMonth->daysInMonth;
    }

    /**
     * calculate line usage fee and daily usage fee of current month and next month
     * @param $currentMonthFees
     * @param $nextMonthFees
     * @param $lineUsageFee
     * @param $lineStartDate
     */
    public function calculateFee(&$currentMonthFees, &$nextMonthFees, $lineUsageFee, $lineStartDate) {
        $diffDay = $lineStartDate->diffInDays($this->endDateOfCurrentMonth, false);
        //Add fee to current month fees
        $this->calculateFeeByMonth($lineUsageFee,$diffDay, $this->numberOfDaysInCurrentMonth, $currentMonthFees);

        $diffDay = $lineStartDate->diffInDays($this->endDateOfNextMonth, false);
        //Add fee to next month fees
        $this->calculateFeeByMonth($lineUsageFee,$diffDay, $this->numberOfDaysInNextMonth, $nextMonthFees);
    }

    /**
     * calculate line usage fee and daily usage fee by month
     * @param $lineUsageFee
     * @param $diffDay
     * @param $numberOfDaysInMonth
     * @param $monthFees
     */
    private function calculateFeeByMonth($lineUsageFee, $diffDay, $numberOfDaysInMonth, &$monthFees) {
        if ($diffDay >= 0) {
            $usageDays = $diffDay + 1;

            if ($usageDays < $numberOfDaysInMonth) {
                $monthFees['daily_line_usage_fee'] += round($usageDays * $lineUsageFee / $numberOfDaysInMonth, 2);
                $monthFees['daily_line_usage_number'] ++;
            }
            else {
                $monthFees['line_usage_fee'] += $lineUsageFee;
                $monthFees['line_usage_number'] ++;
            }
        }
    }
}
