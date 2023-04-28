<?php

namespace FileRenamer\Filehandler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Renamer
{
    private const SEPERATOR = '__';
    private const FILE_SUFFIX = '.';
    private string $path;
    private string $newFileName;
    private bool $deleteOldFiles;

    public function __construct(string $path, string $newFileName)
    {
        $this->path =$path;
        $this->newFileName = $newFileName;
    }

    public function renameFiles(): array
    {
        $files=[];
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->path)) {
            throw new \RuntimeException("Provided path " . $this->path . " does not exists");
        }

        $finder = new Finder();

        $finder->files()->in($this->path);

        if (!$finder->hasResults()) {
            return [];
        }
        foreach ($finder as $file) {
            $replacement = $this->newFileName .
                self::SEPERATOR .
                $this->getCurrentDateTime() .
                self::FILE_SUFFIX .
                $file->getExtension();

            $fileSystem->rename(
                $file->getRealPath(),
                $this->path . "/" . $replacement,
                true
            );
            $files[] = [
                $file->getFilename() => $replacement
            ];
        }
        return $files;
    }

    private function getCurrentDateTime():string
    {
        $dt = new \DateTime();
        return $dt->format('Y-m-d_His_u');
    }

}
