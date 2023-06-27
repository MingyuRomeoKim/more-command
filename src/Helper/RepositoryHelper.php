<?php

namespace MingyuKim\MoreCommand\Helper;

use Illuminate\Support\Facades\File;

class RepositoryHelper
{
    protected string $repository_namespace;
    protected string $repository_path;
    protected string $base_repository_class;
    protected string $base_repository_interface;
    protected string $print;

    protected const BASE_TEMPLATE_PATH = "BaseTemplate";

    public function __construct
    (
        string $repository_namespace,
        string $repository_path,
        string $base_repository_class,
        string $base_repository_interface,
        string $print = ''
    )
    {
        $this->repository_namespace = $repository_namespace;
        $this->repository_path = $repository_path;
        $this->base_repository_class = $base_repository_class;
        $this->base_repository_interface = $base_repository_interface;
        $this->print = $print;
    }

    public function checkDefaultClassAndInterface(): void
    {

        $base_template_file = base_path() . $this->repository_path . "/" . self::BASE_TEMPLATE_PATH . "/";

        if (!$this->checkBaseTemplateFile($base_template_file, $this->base_repository_class)) {
            $baseRepositoryClassContent = $this->getBaseClassTemplateContents();
            $base_repository_class_path = $base_template_file . $this->base_repository_class;
            $result = (new FileMaker($base_repository_class_path, $baseRepositoryClassContent))->generate();

            if ($this->print) {
                dump("Base_repository_class Make Result :: " . $result);
            }
        }

        if (!$this->checkBaseTemplateFile($base_template_file, $this->base_repository_interface)) {
            $baseRepositoryInterfaceContent = $this->getBaseInterfaceTemplateContent('');
            $base_repository_interface_path = $base_template_file . $this->base_repository_interface;
            $result = (new FileMaker($base_repository_interface_path, $baseRepositoryInterfaceContent))->generate();

            if ($this->print) {
                dump("Base_repository_interface Make Result :: " . $result);
            }
        }
    }

    public function getRepositoryTemplateContents(string $model_name, string $model_namespace, string $repository_name): string
    {
        $repositoryStubPath = $this->getStubFilePath('class');
        $repositoryNameSpace = $this->repository_namespace;
        if ($model_name !== $model_namespace) {
            $more_directory_path = str_replace("/", "\\", dirname($model_namespace));
            $repositoryNameSpace .= "\\" . $more_directory_path;
        }

        $model_namespace = str_replace("/", "\\", $model_namespace);

        return (new ContentMaker(
            __DIR__ . "/../" . $repositoryStubPath,
            [
                "REPOSITORY_DEFAULT_NAMESPACE" => $this->repository_namespace,
                "REPOSITORY_NAMESPACE" => $repositoryNameSpace,
                "MODEL_NAME" => $model_name,
                "MODEL_NAMESPACE" => $model_namespace,
                "REPOSITORY_NAME" => $repository_name
            ]
        ))->render();
    }

    protected function getStubFilePath(string $type = 'class'): string
    {
        $stub = match ($type) {
            'class' => '/Stub/Repository/repository.stub',
            'baseClass' => '/Stub/Repository/BaseRepository.stub',
            'baseInterface' => '/Stub/Repository/RepositoryInterface.stub'
        };

        return $stub;
    }

    protected function checkBaseTemplateFile(string $base_path, string $file_name = ''): bool
    {
        $base_template_file = $base_path . $file_name;

        if (!File::exists($base_template_file)) {
            return false;
        } else {
            return true;
        }
    }

    protected function getBaseClassTemplateContents(): string
    {
        $baseRepositoryClassStubPath = $this->getStubFilePath('baseClass');

        return (new ContentMaker(
            __DIR__ . "/../" . $baseRepositoryClassStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render();
    }

    protected function getBaseInterfaceTemplateContent(): string
    {
        $baseRepositoryInterfaceStubPath = $this->getStubFilePath('baseInterface');

        return (new ContentMaker(
            __DIR__ . "/../" . $baseRepositoryInterfaceStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render();
    }
}