<?php
declare(strict_types=1);

namespace MingyuKim\MoreCommand\Libraries;

use MingyuKim\MoreCommand\Traits\SingletonTrait;

class DataLibrary
{
    use SingletonTrait;

    /**
     * @param string|null $beforeType
     * @param string|null $afterType
     * @param mixed $data
     * @return mixed
     */
    public function convert(?string $beforeType, ?string $afterType, mixed $data): mixed
    {
        if (!$data) return null;

        return match ($beforeType) {
            'string' => match ($afterType) {
                'string' => $data,
                'array' => $this->convertStringToArray($data)
            },
            'array' => match ($afterType) {
                'string' => $this->convertArrayToString($data, 'JUST_USE'),
                'array' => $data
            },
        };
    }

    /**
     * @param array|null $data
     * @param string $option
     * @return string|null
     */
    protected function convertArrayToString(?array $data, string $option = 'JUST_USE'): ?string
    {
        return match ($option) {
            'JUST_USE' => json_encode($data),
            default => json_encode($data, (int)$option)
        };
    }

    /**
     * @param string|null $data
     * @return array|null
     */
    protected function convertStringToArray(?string $data): ?array
    {
        return json_decode($data, true);
    }

}
