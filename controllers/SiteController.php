<?php

namespace app\controllers;

use app\services\ExportHistoryService;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Export to CSV
     *
     * @param string $exportType
     * @return string
     */
    public function actionExport(string $exportType): string
    {
        $exportService = new ExportHistoryService($exportType, Yii::$app->request->queryParams);
        if (!$exportService->validateExportType()) {
            return $this->renderError(
                'Incorrect export type',
                sprintf('%s - is incorrect export type. Please set another.', $exportType)
            );
        }

        return $this->render('export', ['service' => $exportService]);
    }

    /**
     * Show error page
     *
     * @param string $name
     * @param string $message
     * @return string
     */
    private function renderError(string $name, string $message): string
    {
        return $this->render('error', [
            'name' => $name,
            'message' => $message,
        ]);
    }
}
