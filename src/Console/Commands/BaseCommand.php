<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    public function getRepositoryNamespace()
    {
        return config('more-command.repository-namespace') ?? 'App';
    }

    public function getTraitNamespace()
    {
        return config('more-command.trait-namespace') ?? 'App';
    }
}
