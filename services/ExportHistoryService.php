<?php

namespace app\services;

use app\enum\EventTypeEnum;
use app\models\History;
use app\models\search\HistorySearch;
use Yii;
use yii\data\ActiveDataProvider;

class ExportHistoryService
{
    private const BATCH_SIZE = 2000;

    /** @var HistorySearch */
    private $model;

    /** @var string */
    private $exportType;

    /** @var array */
    private $queryParameters;

    /**
     * Set export type, model and query parameters
     *
     * @param string $exportType
     * @param array $queryParameters
     */
    public function __construct(string $exportType, array $queryParameters = [])
    {
        $this->model = new HistorySearch();
        $this->exportType = $exportType;
        $this->queryParameters = $queryParameters;
    }

    /**
     * Get export model
     *
     * @return HistorySearch
     */
    public function getModel(): HistorySearch
    {
        return $this->model;
    }

    /**
     * Get export type
     *
     * @return string
     */
    public function getExportType(): string
    {
        return $this->exportType;
    }

    /**
     * Get query parameters
     *
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * Check export type
     *
     * @return bool
     */
    public function validateExportType(): bool
    {
        return in_array($this->getExportType(), Yii::$app->params['allowedExportTypes']);
    }

    /**
     * Get parameters for export widget
     *
     * @return array
     */
    public function getWidgetParameters(): array
    {
        return [
            'dataProvider' => $this->getDataProvider(),
            'columns' => $this->getExportColumns(),
            'exportType' => $this->getExportType(),
            'batchSize' => self::BATCH_SIZE,
            'filename' => $this->getExportFileName(),
        ];
    }

    /**
     * Get data provider
     *
     * @return ActiveDataProvider
     */
    private function getDataProvider(): ActiveDataProvider
    {
        return $this->getModel()->search($this->getQueryParameters());
    }

    /**
     * Array of columns for export
     *
     * @return array[]
     */
    private function getExportColumns(): array
    {
        return [
            [
                'attribute' => 'ins_ts',
                'label' => $this->getColumnLabel('Date'),
                'format' => 'datetime'
            ],
            [
                'label' => $this->getColumnLabel('User'),
                'value' => function (History $model) {
                    return isset($model->user) ? $model->user->username : $this->getColumnLabel('System');
                }
            ],
            [
                'label' => $this->getColumnLabel('Type'),
                'value' => function (History $model) {
                    return $model->object;
                }
            ],
            [
                'label' => $this->getColumnLabel('Event'),
                'value' => function (History $model) {
                    return $model->eventText;
                }
            ],
            [
                'label' => $this->getColumnLabel('Message'),
                'value' => function (History $model) {
                    return strip_tags(EventTypeEnum::getEventBodyByModel($model));
                }
            ]
        ];
    }

    /**
     * Get column label
     *
     * @param string $message
     * @return string
     */
    private function getColumnLabel(string $message): string
    {
        return Yii::t('app', $message);
    }

    /**
     * Get export file name
     *
     * @return string
     */
    private function getExportFileName(): string
    {
        return sprintf('history-%s', time());
    }
}