<?php

class JsonStorage
{
    public function __construct(private readonly string $file) {}

    public function all(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function saveAll(array $data): void
    {
        $fp = fopen($this->file, 'c+');

        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
        }

        fclose($fp);
    }
}