<?php

namespace app\widgets\HistoryList;

use app\services\HistoryService;
use yii\base\Widget;

class HistoryList extends Widget
{
    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('main', (new HistoryService())->getRenderData());
    }
}
