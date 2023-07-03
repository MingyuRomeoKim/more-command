<?php

namespace MingyuKim\MoreCommand\Helper;

class ViewHelper
{
    protected string $print;

    public function __construct
    (
        string $print = ''
    )
    {
        $this->print = $print;
    }

    public function getViewTemplateContents(): string
    {
        $viewStubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            __DIR__ . "/../" . $viewStubPath, [])
        )->render();
    }

    protected function getStubFilePath(string $type = 'default'): string
    {
        $stub = match ($type) {
            'default' => '/Stub/View/view.stub'
        };

        return $stub;
    }
}
