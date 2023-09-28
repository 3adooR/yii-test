<?php

namespace app\enum;

use Yii;

class TaskStatusEnum
{
    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_CANCEL = 3;

    /**
     * @return array
     */
    public static function getStatusTexts(): array
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_DONE => Yii::t('app', 'Complete'),
            self::STATUS_CANCEL => Yii::t('app', 'Cancel'),
        ];
    }

    /**
     * @param int|null $value
     * @return string|null
     */
    public static function getStatusTextByValue(?int $value): ?string
    {
        return self::getStatusTexts()[$value] ?? $value;
    }
}