<?php

namespace App\Service;

use App\Exception\FileTooLargeException;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use const PATHINFO_FILENAME;

/**
 * To upload files on the server.
 */
class FileUploader
{
    /**
     * @param SluggerInterface $slugger   для приведения имени файла к виду подходящему для URL
     * @param string           $uploadDir директория загрузки файлов, базовое место где сохраняются загрузки
     */
    public function __construct(
        private readonly SluggerInterface $slugger,
        #[Autowire('%uploadDirPath%')]
        private readonly string $uploadDir,
        #[Autowire('%maxFileSize%')]
        private readonly int $allowedFileSize,
        private readonly UniqueIdProviderInterface $uniqueIdProvider,
    ) {
    }

    /**
     * Загружает файл в определенную директорию в загрузках, возвращает его имя.
     *
     * @param string $uploadSubDir директория в загрузках, куда мы поместим файл
     *
     * @return string имя файла
     *
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file, string $uploadSubDir): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $uniqueId = $this->uniqueIdProvider->get();
        $fileName = $safeFilename.'-'.$uniqueId.'.'.$file->guessExtension();

        $allowedSizeInBytes = $this->allowedFileSize * 1024 * 1024;
        if ($file->getSize() > $allowedSizeInBytes) {
            throw new FileTooLargeException('File is larger than allowed');
        }

        $targetDir = $this->uploadDir.'/'.$uploadSubDir;
        $file->move($targetDir, $fileName);

        return $fileName;
    }
}
