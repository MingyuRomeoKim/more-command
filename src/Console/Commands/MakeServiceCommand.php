<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\ServiceHelper;

class MakeServiceCommand extends BaseCommand
{
    protected string $service_namespace;
    protected string $service_path;
    protected ServiceHelper $serviceHelper;

    public function __construct()
    {
        parent::__construct();
        $this->service_namespace = $this->getServiceBaseNamespace();
        $this->service_path = $this->getServiceBasePath();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {--print} {serviceName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'serviceName 이름의 service 를 생성한다.
                                serviceName (예시) TestService || Test/TestService ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceName = $this->argument('serviceName');
        $print = $this->option('print');

        if (empty($serviceName)) {
            dump("You must have serviceName option");
            Command::FAILURE;
        }

        try {
            // [step 1] create service file
            if (strpos($serviceName, "/") > -1) {
                $dumpArray = explode("/", $serviceName);
                $serviceName = array_pop($dumpArray);
                $this->service_namespace .= "\\" . implode("\\", $dumpArray);
                $this->service_path .= "/" . implode("/", $dumpArray);
            }

            // [step 2] define repository name and namespace using in service.
            $repositoryName = Str::replace("Service", "Repository", $serviceName);
            $repositoryNameSpace = Str::replace("Services", "Repositories", $this->service_namespace);

            // [step 3] create service class
            $this->serviceHelper = new ServiceHelper(service_namespace: $this->service_namespace);
            $this->serviceHelper->setRepositoryName(repository_name: $repositoryName);
            $this->serviceHelper->setRepositoryNamespace(repository_namespace: $repositoryNameSpace);
            $service_file_content = $this->serviceHelper->getServiceTemplateContents(service_name: $serviceName);
            $trait_real_path = base_path() . $this->service_path . "/" . $serviceName . ".php";

            if ($print) {
                dump($trait_real_path);
                dump($service_file_content);
            } else {
                (new FileMaker(path: $trait_real_path, contents: $service_file_content))->generate();
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }

    }
}