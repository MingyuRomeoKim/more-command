<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Helper;

class ServiceHelper
{
    protected string $service_namespace;
    protected string $print;

    /**
     * @param string $service_namespace
     * @param string $print
     */
    public function __construct(string $service_namespace, string $print = '')
    {
        $this->service_namespace = $service_namespace;
        $this->print = $print;
    }

    /**
     * @param string $service_name
     * @return string|null
     */
    public function getServiceTemplateContents(string $service_name): ?string
    {
        $stubPath = $this->getStubFilePath('default');

        return (new ContentMaker(
            __DIR__ . "/../" . $stubPath,
            [
                "SERVICE_NAMESPACE" => $this->service_namespace,
                "SERVICE_NAME" => $service_name,
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
}