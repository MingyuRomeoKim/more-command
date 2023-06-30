<?php

namespace MingyuKim\MoreCommand\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    const REPOSITORY_BASE_FORDER_NAME = "Repositories";
    const TRAIT_BASE_FORDER_NAME = "Traits";
    const SERVICE_BASE_FOLDER_NAME = "Services";

    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    */
    public function getRepositoryNamespaceFromConfig(): string
    {
        return config('more-command.repository-namespace') ?? 'App';
    }

    public function getRepositoryBaseNamespace(): string
    {
        return $this->getRepositoryNamespaceFromConfig() . "\\" . self::REPOSITORY_BASE_FORDER_NAME;
    }

    public function getRepositoryBasePath(): string
    {
        return "/" . strtolower($this->getRepositoryNamespaceFromConfig()) . "/" . self::REPOSITORY_BASE_FORDER_NAME;
    }

    /*
    |--------------------------------------------------------------------------
    | Trait
    |--------------------------------------------------------------------------
    */
    public function getTraitNamespaceFromConfig(): string
    {
        return config('more-command.trait-namespace') ?? 'App';
    }

    public function getTraitBaseNamespace(): string
    {
        return $this->getTraitNamespaceFromConfig() . "\\" . self::TRAIT_BASE_FORDER_NAME;
    }

    public function getTraitBasePath(): string
    {
        return "/" . strtolower($this->getTraitNamespaceFromConfig()) . "/" . self::TRAIT_BASE_FORDER_NAME;
    }

    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */
    public function getServiceNamespaceFronConfig(): string
    {
        return config('more-command.service-namespace') ?? 'App';
    }

    public function getServiceBaseNamespace(): string
    {
        return $this->getServiceNamespaceFronConfig(). "\\". self::SERVICE_BASE_FOLDER_NAME;
    }

    public function getServiceBasePath(): string
    {
        return "/". strtolower($this->getServiceNamespaceFronConfig()). "/". self::SERVICE_BASE_FOLDER_NAME;
    }

}
