<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    public function getRepositoryNamespace()
    {
        return config('moreCommand.repository-namespace') ?? 'App';
    }
}