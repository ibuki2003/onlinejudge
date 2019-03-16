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
        return 'zipExtractTo';
    }

    /**
     * Extract zip file into destination directory.
     *
     * @param string $path Destination directory
     * @param string $zipFilePath The path to the zip file.
     *
     * @return bool True on success, false on failure.
     */
    public function handle($zip, $path)
    {
        $path = $this->cleanPath($path);

        for ($i = 0; $i < $zip->numFiles; ++$i)
        {
            $zipEntryName = $zip->getNameIndex($i);
            $destination = $path . DIRECTORY_SEPARATOR . $this->cleanPath($zipEntryName);
            if ($this->isDirectory($zipEntryName))
            {
                $this->filesystem->createDir($destination);
                continue;
            }
            $this->filesystem->putStream($destination, $zip->getStream($zipEntryName));
        }

        return true;
    }

    private function isDirectory($zipEntryName)
    {
        return substr($zipEntryName, -1) ===  '/';
    }

    private function cleanPath($path)
    {
        if(!$this->isDirectory($path)) $path .= '/';
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

}
