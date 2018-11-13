<?php
namespace App\Filesystem\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;
use ZipArchive;

class ZipExtractTo extends AbstractPlugin
{
    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return 'extractTo';
    }

    /**
     * Extract zip file into destination directory.
     *
     * @param string $path Destination directory
     * @param string $zipFilePath The path to the zip file.
     *
     * @return bool True on success, false on failure.
     */
    public function handle($path, $zipFilePath)
    {
        $path = $this->cleanPath($path);

        $zipArchive = new ZipArchive();
        if ($zipArchive->open($zipFilePath) !== true) 
        {
            return false;
        }

        for ($i = 0; $i < $zipArchive->numFiles; ++$i) 
        {
            $zipEntryName = $zipArchive->getNameIndex($i);
            $destination = $path . DIRECTORY_SEPARATOR . $this->cleanPath($zipEntryName);
            if ($this->isDirectory($zipEntryName)) 
            {
                $this->filesystem->createDir($destination);
                continue;
            }
            $this->filesystem->putStream($destination, $zipArchive->getStream($zipEntryName));
        }

        return true;
    }

    private function isDirectory($zipEntryName) 
    {
        return substr($zipEntryName, -1) ===  '/';
    }

    private function cleanPath($path)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

}
