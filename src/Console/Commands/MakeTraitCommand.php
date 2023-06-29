<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;
use MingyuKim\MoreCommand\Helper\FileMaker;
use MingyuKim\MoreCommand\Helper\TraitHelper;

class MakeTraitCommand extends BaseCommand
{
    protected string $trait_namespace;
    protected string $trait_path;
    protected TraitHelper $traitHelper;

    public function __construct()
    {
        parent::__construct();
        $this->trait_namespace = $this->getTraitBaseNamespace();
        $this->trait_path = $this->getTraitBasePath();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {--print} {traitName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'traitName 이름의 Trait을 생성한다.
                                traitName (예시) TestTrait || Test/TestTrait ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $traitName = $this->argument('traitName');
        $print = $this->option('print');

        if (empty($traitName)) {
            dump("You must have traitName option");
            Command::FAILURE;
        }

        try {
            // [step 1] create trait file
            if (strpos($traitName, "/") > -1) {
                $dumpArray = explode("/", $traitName);
                $traitName = array_pop($dumpArray);
                $this->trait_namespace .= "\\" . implode("\\", $dumpArray);
                $this->trait_path .= "/" . implode("/", $dumpArray);
            }

            $this->traitHelper = new TraitHelper($this->trait_namespace, $this->trait_path);
            $trait_file_content = $this->traitHelper->getTraitTemplateContents($traitName);
            $trait_real_path = base_path() . $this->trait_path . "/" . $traitName . ".php";

            if ($print) {
                dump($trait_real_path);
                dump($trait_file_content);
            } else {
                (new FileMaker($trait_real_path, $trait_file_content))->generate();
            }
        } catch (\Exception $exception) {
            dump($exception->getMessage());
            Command::FAILURE;
        }

    }

}
