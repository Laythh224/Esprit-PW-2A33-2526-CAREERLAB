<?php

class RecaptchaService
{
    private array $config;

    public function __construct()
    {
        $this->config = [
            'enabled' => true,
            'version' => 'v2',
            'site_key' => '6LemKdcsAAAAABODvX6PBJ-MRkCWA_tjBrG3CNwB',
            'secret_key' => '6LemKdcsAAAAAIu1bAE1ckFcMNT9Pk8e3I8VUr94',
        ];
    }

    public function isEnabled(): bool
    {
        return $this->config['enabled'] ?? false;
    }

    public function getVersion(): string
    {
        return $this->config['version'] ?? 'v2';
    }

    public function getSiteKey(): string
    {
        return $this->config['site_key'] ?? '';
    }

    public function getSecretKey(): string
    {
        return $this->config['secret_key'] ?? '';
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
