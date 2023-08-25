<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Helper;

class TraitHelper
{
    protected string $trait_namespace;

    /**
     * @param string $trait_namespace
     */
    public function __construct(string $trait_namespace)
    {
        $this->trait_namespace = $trait_namespace;
    }

    /**
     * @param string $trait_name
     * @return string|null
     */
    public function getTraitTemplateContents(string $trait_name, bool $isSingleTon = false): ?string
    {

        $traitStubPath = $isSingleTon ? $this->getStubFilePath('singleTon') :$this->getStubFilePath('default');

        return (new ContentMaker(
            path: __DIR__ . "/../" . $traitStubPath,
            replaces: [
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
            'default' => '/Stub/Trait/trait.stub',
            'singleTon' => '/Stub/Trait/singleton-trait.stub'
        };

        return $stub;
    }
}
