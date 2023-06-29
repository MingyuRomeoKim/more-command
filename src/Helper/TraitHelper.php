<?php

namespace MingyuKim\MoreCommand\Helper;

class TraitHelper
{
    protected string $trait_namespace;
    protected string $print;

    public function __construct
    (
        string $trait_namespace,
        string $print = ''
    )
    {
        $this->trait_namespace = $trait_namespace;
        $this->print = $print;
    }

    public function getTraitTemplateContents(string $trait_name): string
    {
        $traitStubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            __DIR__ . "/../" . $traitStubPath,
            [
                "TRAIT_NAMESPACE" => $this->trait_namespace,
                "TRAIT_NAME" => $trait_name,
            ]
        ))->render();
    }

    protected function getStubFilePath(string $type = 'default'): string
    {
        $stub = match ($type) {
            'default' => '/Stub/Trait/trait.stub'
        };

        return $stub;
    }
}
