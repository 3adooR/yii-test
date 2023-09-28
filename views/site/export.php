<?php
/**
 * @var $service ExportHistoryService
 */

use app\services\ExportHistoryService;
use app\widgets\Export\Export;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

echo Export::widget($service->getWidgetParameters());