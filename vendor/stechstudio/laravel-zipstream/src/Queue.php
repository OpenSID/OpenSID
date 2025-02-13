<?php

namespace STS\ZipStream;

use Illuminate\Support\Collection;
use STS\ZipStream\Contracts\FileContract;

class Queue extends Collection
{
    public function addItem(FileContract $file): self
    {
        if ($this->has($this->queueKey($file->getZipPath())) && config('zipstream.conflict_strategy') === 'rename') {
            $file->setZipPath($this->uniqueZipPath($file->getZipPath()));
        }

        if (!$this->has($this->queueKey($file->getZipPath())) || config('zipstream.conflict_strategy') === 'replace') {
            $this->put($this->queueKey($file->getZipPath()), $file);
        }

        // We are either done, or we had a conflict and config is set to 'skip' (or some invalid value which we'll ignore)

        return $this;
    }

    protected function queueKey($zipPath): string
    {
        return config('zipstream.case_insensitive_conflicts')
            ? strtolower($zipPath)
            : $zipPath;
    }

    protected function uniqueZipPath(string $zipPath): string
    {
        $dirname = trim(pathinfo($zipPath, PATHINFO_DIRNAME), '.');
        $filename = pathinfo($zipPath, PATHINFO_FILENAME);
        $extension = rtrim('.' . pathinfo($zipPath, PATHINFO_EXTENSION), '.');

        $i = 0;

        do {
            $i++;
            $path = ltrim($dirname . DIRECTORY_SEPARATOR . $filename . '_' . $i . $extension, DIRECTORY_SEPARATOR);
        } while ($this->has($this->queueKey($path)));

        return $path;
    }
}