<?php

namespace app\enum;

use Yii;

class CustomerTypeEnum
{
    public const TYPE_LEAD = 'lead';
    public const TYPE_DEAL = 'deal';
    public const TYPE_LOAN = 'loan';

    /**
     * @return array
     */
    public static function getTypeTexts(): array
    {
        return [
            CustomerTypeEnum::TYPE_LEAD => Yii::t('app', 'Lead'),
            CustomerTypeEnum::TYPE_DEAL => Yii::t('app', 'Deal'),
            CustomerTypeEnum::TYPE_LOAN => Yii::t('app', 'Loan'),
        ];
    }

    /**
     * @param string|null $type
     * @return string|null
     */
    public static function getTypeTextByType(?string $type): ?string
    {
        return self::getTypeTexts()[$type] ?? $type;
    }
}