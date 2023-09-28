<?php

namespace app\enum;

use Yii;

class TaskStatesEnum
{
    const STATE_INBOX = 'inbox';
    const STATE_DONE = 'done';
    const STATE_FUTURE = 'future';

    /**
     * @return array
     */
    public static function getStateTexts(): array
    {
        return [
            self::STATE_INBOX => Yii::t('app', 'Inbox'),
            self::STATE_DONE => Yii::t('app', 'Done'),
            self::STATE_FUTURE => Yii::t('app', 'Future')
        ];
    }

    /**
     * @param string|null $state
     * @return string|null
     */
    public function getStateText(?string $state): ?string
    {
        return self::getStateTexts()[$state] ?? $state;
    }
}