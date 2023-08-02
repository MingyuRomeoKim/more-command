<?php
declare(strict_types = 1);

namespace MingyuKim\MoreCommand\Helper;

use Illuminate\Support\Facades\File;

class RepositoryHelper
{
    protected string $repository_namespace;
    protected string $repository_default_namespace;
    protected string $repository_path;
    protected string $print;

    protected const BASE_TEMPLATE_PATH = "BaseTemplate";
    protected const BASE_REPOSITORY_CLASS = "BaseRepository.php";
    protected const BASE_REPOSITORY_INTERFACE = "RepositoryInterface.php";

    /**
     * @param string $repository_namespace
     * @param string $repository_path
     * @param string $print
     */
    public function __construct(string $repository_namespace, string $repository_path, string $print = '')
    {
        $this->repository_namespace = $repository_namespace;
        $this->repository_path = $repository_path;
        $this->print = $print;

        $this->repository_default_namespace = config('more-command.repository-namespace') ? config('more-command.repository-namespace') . "\Repositories" : 'App\Repositories';
    }

    /**
     * @param string $repository_path
     */
    public function setRepositoryPath(string $repository_path): void
    {
        $this->repository_path = $repository_path;
    }

    /**
     * @param string $repository_namespace
     */
    public function setRepositoryNamespace(string $repository_namespace): void
    {
        $this->repository_namespace = $repository_namespace;
    }

    /**
     * @return void
     */
    public function checkDefaultClassAndInterface(): void
    {

        $base_template_file = base_path() . $this->repository_path . "/" . self::BASE_TEMPLATE_PATH . "/";

        if (!$this->checkBaseTemplateFile($base_template_file, self::BASE_REPOSITORY_CLASS)) {
            $baseRepositoryClassContent = $this->getBaseClassTemplateContents();
            $base_repository_class_path = $base_template_file . self::BASE_REPOSITORY_CLASS;
            $result = (new FileMaker($base_repository_class_path, $baseRepositoryClassContent))->generate();

            if ($this->print) {
                dump("Base_repository_class Make Result :: " . $result);
            }
        }

        if (!$this->checkBaseTemplateFile($base_template_file, self::BASE_REPOSITORY_INTERFACE)) {
            $baseRepositoryInterfaceContent = $this->getBaseInterfaceTemplateContent('');
            $base_repository_interface_path = $base_template_file . self::BASE_REPOSITORY_INTERFACE;
            $result = (new FileMaker($base_repository_interface_path, $baseRepositoryInterfaceContent))->generate();

            if ($this->print) {
                dump("Base_repository_interface Make Result :: " . $result);
            }
        }
    }

    /**
     * @param string $model_name
     * @param string $model_namespace
     * @param string $repository_name
     * @return string
     */
    public function getRepositoryTemplateContents(string $model_name, string $model_namespace, string $repository_name): string
    {
        $repositoryStubPath = $this->getStubFilePath('class');

        return (new ContentMaker(
            __DIR__ . "/../" . $repositoryStubPath,
            [
                "REPOSITORY_DEFAULT_NAMESPACE" => $this->repository_default_namespace,
                "REPOSITORY_NAMESPACE" => $this->repository_namespace,
                "MODEL_NAME" => $model_name,
                "MODEL_NAMESPACE" => $model_namespace,
                "REPOSITORY_NAME" => $repository_name
            ]
        ))->render();
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getStubFilePath(string $type = 'class'): string
    {
        $stub = match ($type) {
            'class' => '/Stub/Repository/repository.stub',
            'baseClass' => '/Stub/Repository/BaseRepository.stub',
            'baseInterface' => '/Stub/Repository/RepositoryInterface.stub'
        };

        return $stub;
    }

    /**
     * @param string $base_path
     * @param string $file_name
     * @return bool
     */
    protected function checkBaseTemplateFile(string $base_path, string $file_name = ''): bool
    {
        $base_template_file = $base_path . $file_name;

        if (!File::exists($base_template_file)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return string|null
     */
    protected function getBaseClassTemplateContents(): ?string
    {
        $baseRepositoryClassStubPath = $this->getStubFilePath('baseClass');

        return (new ContentMaker(
            __DIR__ . "/../" . $baseRepositoryClassStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render() ?? null;
    }

    /**
     * @return string|null
     */
    protected function getBaseInterfaceTemplateContent(): ?string
    {
        $baseRepositoryInterfaceStubPath = $this->getStubFilePath('baseInterface');

        return (new ContentMaker(
            __DIR__ . "/../" . $baseRepositoryInterfaceStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render() ?? null;
    }
}
