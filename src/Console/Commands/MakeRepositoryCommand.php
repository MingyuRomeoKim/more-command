<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\RepositoryHelper;

class MakeRepositoryCommand extends BaseCommand
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
    protected $signature = 'make:repository {--print} {repositoryName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'repositoryName 이름의 레포지토리를 생성한다.
                                repositoryName (예시) TestRepository || Test/TestRepository ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repositoryName = $this->argument('repositoryName');
        $print = $this->option('print');

        if (empty($repositoryName)) {
            dump("You must have repositoryName option");
            Command::FAILURE;
        }

        try {
            // [step 1] check base interface & Class
            $this->repositoryHelper = new RepositoryHelper($this->repository_namespace, $this->repository_path, $print);
            $this->repositoryHelper->checkDefaultClassAndInterface();

            // [step 2] init properties
            if (strpos($repositoryName, "/") > -1) {
                $dumpArray = explode("/", $repositoryName);
                $repositoryName = array_pop($dumpArray);
                $model_name = str_replace("Repository", "", $repositoryName);
                $model_namespace = implode("\\", $dumpArray) . "\\" . $model_name;
                $this->repository_namespace .= "\\" . implode("\\", $dumpArray);
                $this->repository_path .= "/" . implode("/", $dumpArray);

                $this->repositoryHelper->setRepositoryPath($this->repository_path);
                $this->repositoryHelper->setRepositoryNamespace($this->repository_namespace);
            } else {
                $model_name = str_replace("Repository", "", $repositoryName);
                $model_namespace = $model_name;
            }

            // [step 3] createRepositoryContents
            $repository_file_content = $this->repositoryHelper->getRepositoryTemplateContents($model_name, $model_namespace, $repositoryName);
            $repository_real_path = base_path() . $this->repository_path . "/" . $repositoryName . ".php";

            if ($print) {
                dump($repository_file_content);
                dump($repository_real_path);
            } else {
                (new FileMaker($repository_real_path, $repository_file_content))->generate();
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }

    }

}
