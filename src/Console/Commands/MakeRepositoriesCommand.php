<?php
declare(strict_types=1);

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
    protected RepositoryHelper $repositoryHelper;

    public function __construct()
    {
        parent::__construct();
        $this->repository_namespace = $this->getRepositoryBaseNamespace();
        $this->repository_path = $this->getRepositoryBasePath();
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
        $this->repositoryHelper = new RepositoryHelper($this->repository_namespace, $this->repository_path, $print);

        try {
            // [step 1] init
            $relativePathnameInModels = $this->getFileDataInModels(wanted: 'relativePathname');
            $filenameInModels = $this->getFileDataInModels(wanted: 'filename');

            // [step 2] create base interface & Class
            $this->repositoryHelper->checkDefaultClassAndInterface();

            // [step 3] redefine repository fileName and relativePath
            $relativePathnameInRepositories = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $relativePathnameInModels);
            $filenameInRepositories = array_map(function ($value) {
                return str_replace(".php", "Repository.php", $value);
            }, $filenameInModels);

            // [step 4] create repository class using models
            foreach ($relativePathnameInRepositories as $index => $repositoryRelativePathName) {
                $model_name = str_replace(".php", "", $filenameInModels[$index]);
                $model_path = str_replace(".php", "", $relativePathnameInModels[$index]);
                $model_namespace = str_replace("/", "\\", $model_path);

                $repository_name = str_replace(".php", "", $filenameInRepositories[$index]);
                $repository_namespace = $this->repository_namespace;
                $repository_path = $this->repository_path;

                if (strpos($model_path, "/") > -1) {
                    $more_directory_path = str_replace("/", "\\", dirname($model_path));
                    $repository_namespace .= "\\" . str_replace("/", "\\", dirname($model_path));
                    $repository_path .= "/" . $more_directory_path;
                }

                $this->repositoryHelper->setRepositoryNamespace(repository_namespace: $repository_namespace);
                $this->repositoryHelper->setRepositoryPath(repository_path: $repository_path);

                $repository_file_content = $this->repositoryHelper->getRepositoryTemplateContents(model_name: $model_name, model_namespace: $model_namespace, repository_name: $repository_name);
                $repository_real_path = base_path() . $this->repository_path . "/" . $repositoryRelativePathName;

                if ($print) {
                    dump($repository_real_path);
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

    /**
     * @param string $wanted
     * @return array
     */
    public function getFileDataInModels(string $wanted = ''): array
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
