<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Helper;

use Illuminate\Support\Str;

class ServiceHelper
{
    protected string $service_namespace;
    protected string $repository_namespace;
    protected string $repository_name;

    public function __construct(string $service_namespace)
    {
        $this->service_namespace = $service_namespace;
    }

    /**
     * @param string $service_name
     * @return string|null
     */
    public function getServiceTemplateContents(string $service_name): ?string
    {
        $stubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            path: __DIR__ . "/../" . $stubPath,
            replaces: [
                "SERVICE_NAMESPACE" => $this->service_namespace,
                "SERVICE_NAME" => $service_name,
                "REPOSITORY_NAMESPACE" => $this->getRepositoryNamespace(),
                "REPOSITORY_NAME" => $this->getRepositoryName(),
                "COMMON" => Str::of($service_name)->replace("Service", "")
            ]
        ))->render() ?? null;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getStubFilePath(string $type = 'default'): string
    {
        $stub = match ($type) {
            'default' => '/Stub/Service/service.stub'
        };

        return $stub;
    }

    /**
     * @param string $repository_namespace
     */
    public function setRepositoryNamespace(string $repository_namespace): void
    {
        $this->repository_namespace = $repository_namespace;
    }

    /**
     * @return string
     */
    public function getRepositoryNamespace(): string
    {
        return $this->repository_namespace ?? '';
    }

    /**
     * @param string $repository_name
     */
    public function setRepositoryName(string $repository_name): void
    {
        $this->repository_name = $repository_name;
    }

    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repository_name ?? '';
    }
}
