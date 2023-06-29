<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use MingyuKim\MoreCommand\Helper\ContentMaker;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\RepositoryHelper;

class MakeRepositoriesCommand extends BaseCommand
{
    protected string $repository_namespace;
    protected string $repository_path;
    protected string $base_repository_class;
    protected string $base_repository_interface;
    protected RepositoryHelper $repositoryHelper;

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
    protected $signature = 'make:repositories {--print}';

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
        $print = $this->option('print');
        $this->repositoryHelper = new RepositoryHelper($this->repository_namespace, $this->repository_path, $this->base_repository_class, $this->base_repository_interface, $print);

        try {
            // [step 1] init
            $relativePathnameInModels = $this->getFileDataInModels('relativePathname');
            $filenameInModels = $this->getFileDataInModels('filename');

            // [step 2] create base interface & Class
            $this->repositoryHelper->checkDefaultClassAndInterface();

            // [step 3] redefine repository fileName and relativePath
            $repositoryRelativePathNames = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $relativePathnameInModels);
            $repositoryFileNames = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $filenameInModels);


            // [step 4] create repository class using models
            foreach ($repositoryRelativePathNames as $index => $repositoryRelativePathName) {
                $model_name = str_replace(".php", "", $filenameInModels[$index]);
                $model_namespace = str_replace(".php", "", $relativePathnameInModels[$index]);

                $repository_name = str_replace(".php", "", $repositoryFileNames[$index]);

                if ($model_name !== $model_namespace) {
                    $more_directory_path = str_replace("/", "\\", dirname($model_namespace));
                    $this->repository_namespace .= "\\" . $more_directory_path;
                    $this->repository_path .= "/". $model_namespace;

                    $this->repositoryHelper->setRepositoryNamespace($this->repository_namespace);
                    $this->repositoryHelper->setRepositoryPath($this->repository_path);
                }

                $model_namespace = str_replace("/", "\\", $model_namespace);

                $repository_file_content = $this->repositoryHelper->getRepositoryTemplateContents($model_name, $model_namespace, $repository_name);
                $repository_real_path = base_path() . $this->repository_path . "/" . $repositoryRelativePathName;

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
