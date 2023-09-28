<?php

namespace app\enum;

use Yii;

class SmsDirectionEnum
{
    const DIRECTION_INCOMING = 0;
    const DIRECTION_OUTGOING = 1;

    /**
     * @return array
     */
    public static function getDirectionTexts(): array
    {
        return [
            self::DIRECTION_INCOMING => Yii::t('app', 'Incoming'),
            self::DIRECTION_OUTGOING => Yii::t('app', 'Outgoing'),
        ];
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    public static function getDirectionTextByValue(?string $value): ?string
    {
        return self::getDirectionTexts()[$value] ?? $value;
    }
}