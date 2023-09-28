<?php

namespace app\services;

use app\models\search\HistorySearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class HistoryService
{
    /** @var HistorySearch */
    private $model;

    /** @var array */
    private $queryParameters;

    /**
     * Set model and query parameters
     *
     * @param array $queryParameters
     */
    public function __construct(array $queryParameters = [])
    {
        $this->model = new HistorySearch();
        $this->queryParameters = $queryParameters;
    }

    /**
     * Get history model
     *
     * @return HistorySearch
     */
    private function getModel(): HistorySearch
    {
        return $this->model;
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
     * Get render data
     *
     * @return array
     */
    public function getRenderData(): array
    {
        return [
            'model' => $this->getModel(),
            'linkExport' => $this->getLinkExport(),
            'dataProvider' => $this->getDataProvider()
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
     * Get link for export list
     *
     * @return string
     */
    private function getLinkExport(): string
    {
        $params = ArrayHelper::merge(
            ['exportType' => Yii::$app->params['defaultExportType']],
            $this->getQueryParameters()
        );
        $params[0] = 'site/export';

        return Url::to($params);
    }
}