<?php

namespace MingyuKim\MoreCommand\Libraries;

use MingyuKim\MoreCommand\Traits\SingletonTrait;

class ApiLibrary
{
    use SingletonTrait;

    private ?string $apiUrl;
    private ?string $method;
    private ?array $requestData;
    private DataLibrary $dataLibrary;

    /**
     * @return string|null
     */
    public function getApiUrl(): ?string
    {
        return $this->apiUrl ?? null;
    }

    /**
     * @param string|null $apiUrl
     */
    public function setApiUrl(?string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    /**
     * @param string|null $method
     */
    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return array|null
     */
    public function getRequestData(): ?array
    {
        return $this->requestData ?? null;
    }

    /**
     * @param array|null $requestData
     */
    public function setRequestData(?array $requestData): void
    {
        $this->requestData = $requestData;
    }

    public function sendRequest(string $returnType = 'string'): string|array
    {
        $this->dataLibrary = DataLibrary::getInstance();
        $returnData = $this->request();

        return $this->dataLibrary->convert(gettype($returnData), $returnType, $returnData);
    }

    private function request(): ?string
    {
        $apiUrl = $this->getApiUrl();
        $options = $this->getOptions();

        $context = stream_context_create($options);
        $result = file_get_contents($apiUrl, false, $context);

        if ($result === FALSE) {
            return null;
        }

        return $result;
    }

    private function getOptions(): ?array
    {
        $this->dataLibrary = DataLibrary::getInstance();

        $returnData = match (strtoupper($this->getMethod())) {
            'GET' => [
                'http' => [
                    'method' => 'GET',
                    'header' => 'Content-type: application/json',
                ],
            ],
            'POST' => [
                'http' => [
                    'header' => 'Content-type: application/json',
                    'method' => 'POST',
                    'content' => $this->dataLibrary->convert(gettype($this->getRequestData()), 'string', $this->getRequestData()),
                ],
            ]
        };

        return $returnData ?? null;
    }

}
