<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Helper;

class ViewHelper
{
    protected string $print;

    /**
     * @param string $print
     */
    public function __construct(string $print = '')
    {
        $this->print = $print;
    }

    /**
     * @return string|null
     */
    public function getViewTemplateContents(): ?string
    {
        $viewStubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            __DIR__ . "/../" . $viewStubPath, [])
        )->render() ?? null;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getStubFilePath(string $type = 'default'): string
    {
        $stub = match ($type) {
            'default' => '/Stub/View/view.stub'
        };

        return $stub;
    }
}
