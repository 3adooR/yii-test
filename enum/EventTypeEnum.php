<?php

namespace app\enum;

use app\dto\EventItemDto;
use app\models\Call;
use app\models\History;
use app\models\Sms;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class EventTypeEnum
{
    public const EVENT_CREATED_TASK = 'created_task';
    public const EVENT_UPDATED_TASK = 'updated_task';
    public const EVENT_COMPLETED_TASK = 'completed_task';

    public const EVENT_INCOMING_SMS = 'incoming_sms';
    public const EVENT_OUTGOING_SMS = 'outgoing_sms';

    public const EVENT_INCOMING_CALL = 'incoming_call';
    public const EVENT_OUTGOING_CALL = 'outgoing_call';

    public const EVENT_INCOMING_FAX = 'incoming_fax';
    public const EVENT_OUTGOING_FAX = 'outgoing_fax';

    public const EVENT_CUSTOMER_CHANGE_TYPE = 'customer_change_type';
    public const EVENT_CUSTOMER_CHANGE_QUALITY = 'customer_change_quality';

    /**
     * Get event
     *
     * @param string $event
     * @return string
     */
    public static function getEventTextByEvent(string $event): string
    {
        return self::getEventTexts()[$event] ?? $event;
    }

    /**
     * @return array
     */
    public static function getEventTexts(): array
    {
        return [
            self::EVENT_CREATED_TASK => Yii::t('app', 'Task created'),
            self::EVENT_UPDATED_TASK => Yii::t('app', 'Task updated'),
            self::EVENT_COMPLETED_TASK => Yii::t('app', 'Task completed'),

            self::EVENT_INCOMING_SMS => Yii::t('app', 'Incoming message'),
            self::EVENT_OUTGOING_SMS => Yii::t('app', 'Outgoing message'),

            self::EVENT_CUSTOMER_CHANGE_TYPE => Yii::t('app', 'Type changed'),
            self::EVENT_CUSTOMER_CHANGE_QUALITY => Yii::t('app', 'Property changed'),

            self::EVENT_OUTGOING_CALL => Yii::t('app', 'Outgoing call'),
            self::EVENT_INCOMING_CALL => Yii::t('app', 'Incoming call'),

            self::EVENT_INCOMING_FAX => Yii::t('app', 'Incoming fax'),
            self::EVENT_OUTGOING_FAX => Yii::t('app', 'Outgoing fax'),
        ];
    }

    /**
     * Get event body by model
     *
     * @param History $model
     * @return string
     */
    public static function getEventBodyByModel(History $model): string
    {
        switch ($model->event) {
            case self::EVENT_CREATED_TASK:
            case self::EVENT_COMPLETED_TASK:
            case self::EVENT_UPDATED_TASK:
                $task = $model->task;
                return "$model->eventText: ".($task->title ?? '');

            case self::EVENT_INCOMING_SMS:
            case self::EVENT_OUTGOING_SMS:
                return $model->sms->message ? $model->sms->message : '';

            case self::EVENT_CUSTOMER_CHANGE_TYPE:
                return "$model->eventText ".
                    (CustomerTypeEnum::getTypeTextByType($model->getDetailOldValue('type')) ?? "not set").' to '.
                    (CustomerTypeEnum::getTypeTextByType($model->getDetailNewValue('type')) ?? "not set");

            case self::EVENT_CUSTOMER_CHANGE_QUALITY:
                return "$model->eventText ".
                    (CustomerQualityEnum::getQualityTextByQuality($model->getDetailOldValue('quality')) ?? "not set").' to '.
                    (CustomerQualityEnum::getQualityTextByQuality($model->getDetailNewValue('quality')) ?? "not set");

            case self::EVENT_INCOMING_CALL:
            case self::EVENT_OUTGOING_CALL:
                /** @var Call $call */
                $call = $model->call;
                return ($call ? $call->totalStatusText.($call->getTotalDisposition(false)
                        ? " <span class='text-grey'>".$call->getTotalDisposition(false)."</span>" : "")
                    : '<i>Deleted</i> ');

            case self::EVENT_OUTGOING_FAX:
            case self::EVENT_INCOMING_FAX:
            default:
                return $model->eventText;
        }
    }

    /**
     * Get template and parameters for render event item
     *
     * @param History $model
     * @return EventItemDto
     */
    public static function getEventItemDto(History $model): EventItemDto
    {
        // default
        $template = '_item_common';
        $parameters = [
            'user' => $model->user,
            'body' => self::getEventBodyByModel($model),
            'footerDatetime' => $model->ins_ts,
        ];

        // set for current event type
        switch ($model->event) {
            case self::EVENT_CREATED_TASK:
            case self::EVENT_COMPLETED_TASK:
            case self::EVENT_UPDATED_TASK:
                $task = $model->task;
                $parameters = ArrayHelper::merge([
                    'iconClass' => 'fa-check-square bg-yellow',
                    'footer' => isset($task->customerCreditor->name) ? "Creditor: ".$task->customerCreditor->name : '',
                ], $parameters);
                break;

            case self::EVENT_INCOMING_SMS:
            case self::EVENT_OUTGOING_SMS:
                $parameters = ArrayHelper::merge([
                    'iconClass' => 'icon-sms bg-dark-blue',
                    'footer' => $model->sms->direction === SmsDirectionEnum::DIRECTION_INCOMING ?
                        Yii::t('app', 'Incoming message from {number}', [
                            'number' => $model->sms->phone_from ?? ''
                        ]) : Yii::t('app', 'Sent message to {number}', [
                            'number' => $model->sms->phone_to ?? ''
                        ]),
                    'iconIncome' => $model->sms->direction === SmsDirectionEnum::DIRECTION_INCOMING,
                ], $parameters);

                break;
            case self::EVENT_OUTGOING_FAX:
            case self::EVENT_INCOMING_FAX:
                $fax = $model->fax;
                $parameters['body'] .= ' - '.
                    (isset($fax->document) ? Html::a(
                        Yii::t('app', 'view document'),
                        $fax->document->getViewUrl(),
                        [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]
                    ) : '');
                $parameters = ArrayHelper::merge([
                    'footer' => Yii::t('app', '{type} was sent to {group}', [
                        'type' => $fax ? FaxTypeEnum::getTypeText($fax->type) : 'Fax',
                        'group' => isset($fax->creditorGroup) ? Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) : ''
                    ]),
                    'iconClass' => 'fa-fax bg-green',
                ], $parameters);
                break;

            case self::EVENT_CUSTOMER_CHANGE_TYPE:
                $template = '_item_statuses_change';
                $parameters = [
                    'model' => $model,
                    'oldValue' => CustomerTypeEnum::getTypeTextByType($model->getDetailOldValue('type')),
                    'newValue' => CustomerTypeEnum::getTypeTextByType($model->getDetailNewValue('type')),
                ];
                break;

            case self::EVENT_CUSTOMER_CHANGE_QUALITY:
                $template = '_item_statuses_change';
                $parameters = [
                    'model' => $model,
                    'oldValue' => CustomerQualityEnum::getQualityTextByQuality($model->getDetailOldValue('quality')),
                    'newValue' => CustomerQualityEnum::getQualityTextByQuality($model->getDetailNewValue('quality')),
                ];
                break;

            case self::EVENT_INCOMING_CALL:
            case self::EVENT_OUTGOING_CALL:
                /** @var Call $call */
                $call = $model->call;
                $answered = $call && $call->status == CallStatusEnum::STATUS_ANSWERED;
                $parameters = ArrayHelper::merge([
                    'content' => $call->comment ?? '',
                    'footer' => isset($call->applicant) ? "Called <span>{$call->applicant->name}</span>" : null,
                    'iconClass' => $answered ? 'md-phone bg-green' : 'md-phone-missed bg-red',
                    'iconIncome' => $answered && $call->direction == CallDirectionEnum::DIRECTION_INCOMING,
                ], $parameters);
                break;

            default:
                $parameters = ArrayHelper::merge(['iconClass' => 'fa-gear bg-purple-light'], $parameters);
                break;
        }

        return new EventItemDto($template, $parameters);
    }
}