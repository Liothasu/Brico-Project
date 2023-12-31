<?php

namespace App\Service;

use App\Repository\ConfigRepository;

class ConfigService
{
    public function __construct(
        private ConfigRepository $configRepository
    ) {}

    public function findAll(): array
    {
        return $this->configRepository->findAllForTwig();
    }

    public function getValue(string $name): mixed
    {
        return $this->configRepository->getValue($name);
    }
}