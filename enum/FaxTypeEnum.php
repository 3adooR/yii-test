<?php

namespace app\enum;

use Yii;

class FaxTypeEnum
{
    const TYPE_POA_ATC = 'poa_atc';
    const TYPE_REVOCATION_NOTICE = 'revocation_notice';

    /**
     * @return array
     */
    public static function getTypeTexts(): array
    {
        return [
            self::TYPE_POA_ATC => Yii::t('app', 'POA/ATC'),
            self::TYPE_REVOCATION_NOTICE => Yii::t('app', 'Revocation'),
        ];
    }

    /**
     * @param string|null $type
     * @return string|null
     */
    public static function getTypeText(?string $type): ?string
    {
        return self::getTypeTexts()[$type] ?? $type;
    }
}