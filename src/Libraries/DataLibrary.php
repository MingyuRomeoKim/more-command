<?php

namespace MingyuKim\MoreCommand\Libraries;

use MingyuKim\MoreCommand\Traits\SingletonTrait;

class DataLibrary
{
    use SingletonTrait;

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

    protected function convertArrayToString(?array $data, string $option = 'JUST_USE'): ?string
    {
        return match ($option) {
            'JUST_USE' => json_encode($data),
            default => json_encode($data, $option)
        };
    }

    protected function convertStringToArray(?string $data): ?array
    {
        return json_decode($data, true);
    }

}
