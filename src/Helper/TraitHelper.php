<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Helper;

class TraitHelper
{
    protected string $trait_namespace;
    protected string $print;

    /**
     * @param string $trait_namespace
     * @param string $print
     */
    public function __construct(string $trait_namespace, string $print = '')
    {
        $this->trait_namespace = $trait_namespace;
        $this->print = $print;
    }

    /**
     * @param string $trait_name
     * @return string|null
     */
    public function getTraitTemplateContents(string $trait_name): ?string
    {
        $traitStubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            __DIR__ . "/../" . $traitStubPath,
            [
                "TRAIT_NAMESPACE" => $this->trait_namespace,
                "TRAIT_NAME" => $trait_name,
            ]
        ))->render() ?? null;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getStubFilePath(string $type = 'default'): string
    {
        $stub = match ($type) {
            'default' => '/Stub/Trait/trait.stub'
        };

        return $stub;
    }
}
