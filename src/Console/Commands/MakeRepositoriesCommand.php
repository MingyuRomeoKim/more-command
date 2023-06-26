<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use MingyuKim\MoreCommand\Helper\ContentMaker;
use MingyuKim\MoreCommand\Helper\FileMaker;

class MakeRepositoriesCommand extends BaseCommand
{
    protected string $repository_namespace;
    protected string $repository_path;
    protected string $base_repository_class;
    protected string $base_repository_interface;
    protected const BASE_TEMPLATE_PATH = "BaseTemplate";

    public function __construct()
    {
        parent::__construct();
        $this->repository_namespace = $this->getRepositoryNamespace() . "\Repositories";
        $this->repository_path = "/" . strtolower($this->getRepositoryNamespace()) . "/Repositories";
        $this->base_repository_class = "BaseRepository.php";
        $this->base_repository_interface = "RepositoryInterface.php";
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Model 폴더를 참고해서 모든 레포지토리를 자동 생성한다.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // step 1] init
            $relativePathnameInModels = $this->getFileDataInModels('relativePathname');
            $filenameInModels = $this->getFileDataInModels('filename');

            // step 2] create base interface & Class
            $this->checkDefaultClassAndInterface();

            // step 3] redefine repository fileName and relativePath
            $repositoryRelativePathNames = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $relativePathnameInModels);
            $repositoryFileNames = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $filenameInModels);


            // step 4] create repository class using models
            foreach ($repositoryRelativePathNames as $index => $repositoryRelativePathName) {
                $model_name = str_replace(".php", "", $filenameInModels[$index]);
                $model_namespace = str_replace(".php", "", $relativePathnameInModels[$index]);

                $repository_name = str_replace(".php", "", $repositoryFileNames[$index]);
                $repository_file_content = $this->getRepositoryTemplateContents($model_name, $model_namespace, $repository_name);
                $repository_real_path = base_path() . $this->repository_path . "/" . $repositoryRelativePathNames[$index];

                if ($this->option('print')) {
                    dump($repository_file_content);
                } else {
                    (new FileMaker($repository_real_path, $repository_file_content))->generate();
                }
            }

            Command::SUCCESS;
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }
    }

    protected function checkDefaultClassAndInterface(): void
    {

        $base_template_file = base_path() . $this->repository_path . "/" . self::BASE_TEMPLATE_PATH . "/";

        if (!$this->checkBaseTemplateFile($base_template_file, $this->base_repository_class)) {
            $baseRepositoryClassContent = $this->getBaseClassTemplateContents();
            $base_repository_class_path = $base_template_file . $this->base_repository_class;
            $result = (new FileMaker($base_repository_class_path, $baseRepositoryClassContent))->generate();

            if ($this->option('print')) {
                dump("Base_repository_class Make Result :: " . $result);
            }
        }

        if (!$this->checkBaseTemplateFile($base_template_file, $this->base_repository_interface)) {
            $baseRepositoryInterfaceContent = $this->getBaseInterfaceTemplateContent('');
            $base_repository_interface_path = $base_template_file . $this->base_repository_interface;
            $result = (new FileMaker($base_repository_interface_path, $baseRepositoryInterfaceContent))->generate();

            if ($this->option('print')) {
                dump("Base_repository_interface Make Result :: " . $result);
            }
        }
    }

    protected function getBaseClassTemplateContents(): string
    {
        $baseRepositoryClassStubPath = $this->getStubFilePath('baseClass');

        return (new ContentMaker(
            __DIR__ . "/../../" . $baseRepositoryClassStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render();
    }

    protected function getBaseInterfaceTemplateContent(): string
    {
        $baseRepositoryInterfaceStubPath = $this->getStubFilePath('baseInterface');

        return (new ContentMaker(
            __DIR__ . "/../../" . $baseRepositoryInterfaceStubPath,
            ["REPOSITORY_NAMESPACE" => $this->repository_namespace]
        ))->render();
    }

    protected function getRepositoryTemplateContents(string $model_name, string $model_namespace, string $repository_name): string
    {
        $repositoryStubPath = $this->getStubFilePath('class');
        $repositoryNameSpace = $this->repository_namespace;
        if ($model_name !== $model_namespace) {
            $more_directory_path = str_replace("/","\\",dirname($model_namespace));
            $repositoryNameSpace .= "\\".$more_directory_path;
        }

        $model_namespace = str_replace("/","\\",$model_namespace);

        return (new ContentMaker(
            __DIR__ . "/../../" . $repositoryStubPath,
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

    public function checkBaseTemplateFile(string $base_path, string $file_name = ''): bool
    {
        $base_template_file = $base_path . $file_name;

        if (!File::exists($base_template_file)) {
            return false;
        } else {
            return true;
        }
    }

    public function getFileDataInModels($wanted = ''): array
    {
        $files = File::allFiles(app_path('Models'));

        $returnData = [];

        foreach ($files as $file) {
            $returnData[] = match ($wanted) {
                'relativePathname' => $file->getRelativePathname(),
                'filename' => $file->getFilename()
            };
        }

        return $returnData;
    }
}
