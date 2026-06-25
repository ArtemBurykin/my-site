<?php

namespace App\Controller\Editorjs;

use App\Exception\FileTooLargeException;
use App\Service\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * To upload files (for instance, images) through the editor js.
 */
#[Route('/editorjs/uploadFile', name: 'editorjs_upload_file', methods: 'POST')]
class UploadFileController extends AbstractController
{
    /**
     * @param string $uploadDirUrl урл директории, куда загружаются файлы
     */
    public function __construct(
        private readonly FileUploader $fileUploader,
        #[Autowire('%uploadDirUrl%')]
        private readonly string $uploadDirUrl,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->createFailResponse(JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->createFailResponse(JsonResponse::HTTP_FORBIDDEN);
        }

        if (0 === $request->files->count()) {
            return $this->createFailResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        $file = $request->files->get('file');
        $editorDir = 'content';

        try {
            $fileName = $this->fileUploader->uploadFile($file, $editorDir);
        } catch (FileTooLargeException) {
            return $this->createFailResponse(JsonResponse::HTTP_REQUEST_ENTITY_TOO_LARGE);
        } catch (Exception) {
            return $this->createFailResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $fileUrl = $this->uploadDirUrl.'/'.$editorDir.'/'.$fileName;

        return new JsonResponse(
            [
                'success' => 1,
                'file' => ['url' => $fileUrl],
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    private function createFailResponse(int $code): JsonResponse
    {
        return new JsonResponse(['success' => 0], $code);
    }
}
