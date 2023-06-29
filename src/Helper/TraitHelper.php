<?php

namespace MingyuKim\MoreCommand\Helper;

class TraitHelper
{
    protected string $trait_namespace;
    protected string $trait_path;
    protected string $print;

    public function __construct
    (
        string $trait_namespace,
        string $trait_path,
        string $print = ''
    )
    {
        $this->trait_namespace = $trait_namespace;
        $this->trait_path = $trait_path;
        $this->print = $print;
    }

    public function getTraitTemplateContents(string $trait_name): string
    {
        $traitStubPath = $this->getStubFilePath('default');
        $repositoryNameSpace = $this->trait_namespace;

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
