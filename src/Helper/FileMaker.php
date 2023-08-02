<?php
declare(strict_types = 1);


namespace MingyuKim\MoreCommand\Helper;

use Illuminate\Filesystem\Filesystem;

class FileMaker
{
    protected string $path;
    protected string $contents;
    protected Filesystem $filesystem;


    /**
     * @param string $path
     * @param string $contents
     * @param Filesystem|null $filesystem
     */
    public function __construct(string $path, string $contents, ?Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return int|bool
     */
    public function generate(): int|bool
    {
        $path = $this->getPath();

        if (!$this->filesystem->exists($path)) {
            $directoryPath = dirname($path);

            if (!$this->filesystem->exists($directoryPath)) {
                $this->filesystem->makeDirectory($directoryPath,0755,true);
            }

            return $this->filesystem->put($path, $this->getContents());
        } else {
            return false;
        }
    }
}
