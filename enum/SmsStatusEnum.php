<?php

namespace app\enum;

use Yii;

class SmsStatusEnum
{
    // incoming
    const STATUS_NEW = 0;
    const STATUS_READ = 1;
    const STATUS_ANSWERED = 2;

    // outgoing
    const STATUS_DRAFT = 10;
    const STATUS_WAIT = 11;
    const STATUS_SENT = 12;
    const STATUS_DELIVERED = 13;
    const STATUS_FAILED = 14;
    const STATUS_SUCCESS = 13;

    /**
     * @return array
     */
    public static function getStatusTexts(): array
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_READ => Yii::t('app', 'Read'),
            self::STATUS_ANSWERED => Yii::t('app', 'Answered'),
            self::STATUS_DRAFT => Yii::t('app', 'Draft'),
            self::STATUS_WAIT => Yii::t('app', 'Wait'),
            self::STATUS_SENT => Yii::t('app', 'Sent'),
            self::STATUS_DELIVERED => Yii::t('app', 'Delivered'),
            self::STATUS_FAILED => Yii::t('app', 'Failed'),
            self::STATUS_SUCCESS => Yii::t('app', 'Success'),
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