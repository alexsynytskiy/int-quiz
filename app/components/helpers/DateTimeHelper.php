<?php
namespace app\components\helpers;

use yii\helpers\ArrayHelper;

/**
 * Class DateTimeHelper
 * @package acp\components\helpers
 */
class DateTimeHelper
{
    /**
     * @param string $date
     *
     * @return array
     */
    public static function parseRangeDate($date) {
        list($firstDate, $secondDate) = array_pad(explode(' - ', $date), 2, null);

        if(empty($firstDate)) {
            $firstDate = null;
        }
        if(empty($secondDate)) {
            $secondDate = null;
        }

        $firstTime  = ArrayHelper::getValue(explode(' ', $firstDate), 1);
        $secondTime = ArrayHelper::getValue(explode(' ', $secondDate), 1);

        $firstTimeSeconds  = ArrayHelper::getValue(explode(':', $firstTime), 2);
        $secondTimeSeconds = ArrayHelper::getValue(explode(':', $secondTime), 2);

        return [
            'firstDate'         => $firstDate,
            'secondDate'        => $secondDate,
            'firstTime'         => $firstTime,
            'secondTime'        => $secondTime,
            'firstTimeSeconds'  => $firstTimeSeconds,
            'secondTimeSeconds' => $secondTimeSeconds,
        ];
    }
}