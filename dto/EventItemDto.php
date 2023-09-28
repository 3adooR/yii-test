<?php

namespace app\dto;

class EventItemDto
{
    public $template;
    public $parameters;

    public function __construct(string $template, array $parameters)
    {
        $this->template = $template;
        $this->parameters = $parameters;
    }
}