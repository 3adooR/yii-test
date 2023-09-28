<?php

use kartik\export\ExportMenu;

return [
    'defaultExportType' => ExportMenu::FORMAT_CSV,
    'allowedExportTypes' => [
        ExportMenu::FORMAT_CSV,
    ],
];