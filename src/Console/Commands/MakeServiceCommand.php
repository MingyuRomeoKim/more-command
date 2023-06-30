<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\ServiceHelper;

class MakeServiceCommand  extends BaseCommand
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

            $this->serviceHelper = new ServiceHelper($this->service_namespace, $this->service_path);
            $service_file_content = $this->serviceHelper->getServiceTemplateContents($serviceName);
            $trait_real_path = base_path() . $this->service_path . "/" . $serviceName . ".php";

            if ($print) {
                dump($trait_real_path);
                dump($service_file_content);
            } else {
                (new FileMaker($trait_real_path, $service_file_content))->generate();
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }

    }
}