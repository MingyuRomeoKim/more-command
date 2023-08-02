<?php
declare(strict_types = 1);

namespace MingyuKim\MoreCommand\Helper;

class ContentMaker
{

    protected string $path;
    protected array $replaces;

    /**
     * @param string $path
     * @param array $replaces
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getContents(): ?string
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$$' . strtoupper($search) . '$$', $replace, $contents);
        }

        return $contents ?? null;
    }

    /**
     * @return string|null
     */
    public function render(): ?string
    {
        return $this->getContents();
    }
}
