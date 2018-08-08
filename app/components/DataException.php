<?php

namespace app\components;

/**
 * Class DataException
 * @package app\components
 */
class DataException extends \yii\base\UserException
{
    /**
     * @inheritdoc
     */
    public function getName() {
        return 'Data Exception';
    }
}
