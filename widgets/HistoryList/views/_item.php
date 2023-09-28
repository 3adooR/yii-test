<?php

use app\enum\EventTypeEnum;
use app\models\History;

/** @var $model History */
$eventItemDto = EventTypeEnum::getEventItemDto($model);
echo $this->render($eventItemDto->template, $eventItemDto->parameters);
