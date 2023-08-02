<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\ViewHelper;

class MakeViewCommand extends BaseCommand
{
    protected string $view_path;
    protected ViewHelper $viewHelper;

    public function __construct()
    {
        parent::__construct();
        $this->view_path = $this->getViewBasePath();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {--print} {viewName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'viewName 이름의 blade file 을 생성한다.
                                viewName (예시)
                                 1) test => test.blade.php
                                 2) Test/test => resources/views/Test/test.blade.php';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewName = $this->argument('viewName');
        $print = $this->option('print');

        if (empty($viewName)) {
            dump("You must have viewName option");
            Command::FAILURE;
        }

        try {
            // [step 1] create view file
            if (strpos($viewName, "/") > -1) {
                $dumpArray = explode("/", $viewName);
                $viewName = array_pop($dumpArray);
                $this->view_path .= "/" . implode("/", $dumpArray);
            }

            $this->viewHelper = new ViewHelper($print);
            $view_file_content = $this->viewHelper->getViewTemplateContents();
            $view_real_path = base_path() . $this->view_path . "/" . $viewName . ".blade.php";

            if ($print) {
                dump($view_real_path);
                dump($view_file_content);
            } else {
                (new FileMaker($view_real_path, $view_file_content))->generate();
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }
    }
}