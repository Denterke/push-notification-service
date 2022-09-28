<?php

namespace App\Signature;

use Illuminate\Support\Arr;


class Signer
{
    private $config;

    public function __construct()
    {
        $this->config = config('sign', []);
    }

    public function verify(string $content, string $hash, int $time)
    {
        if (!$this->isWork()) {
            return true;
        }

        if (time() - $time > $this->getLifeTime()) {
            return false;
        }

        return $hash == $this->hash($content, $time);
    }

    private function hash(string $content, int $time)
    {
        return hash('md5', $content . ':' . $time . ':' . $this->getSalt());
    }

    private function getSalt(): string
    {
        return Arr::get($this->config, 'salt', '');
    }

    private function getLifeTime(): int
    {
        return Arr::get($this->config, 'lifetime', 180);
    }

    private function isWork(): bool
    {
        return Arr::get($this->config, 'work', true);
    }
}