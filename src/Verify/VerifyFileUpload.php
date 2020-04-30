<?php

namespace App\Verify;

class VerifyFileUpload
{
    private const ERROR_FILE_UPLOAD = [
        0 => 'Fichier chargé avec succès',
        1 => 'Le fichier chargé est trop lourd',
        2 => 'Le fichier chargé est trop grand',
        3 => 'Erreur de chargement du fichier (partiel)',
        4 => 'Aucun fichier chargé',
        6 => 'Erreur de chargement du fichier (tmp)',
        7 => 'Le fichier n\'a réussi à être sauvegardé',
        8 => 'Erreur inattendu'
    ];

    private const EXTENSIONS = [
        'image/png',
        'image/jpeg',
        'image/jpg'
    ];

    private const SIZE_MAX = 1000000;


    private $filesUpload;
    private $codeError = [];

    /**
     * VerifyFileUpload constructor.
     * @param array $filesUpload
     */
    public function __construct(array $filesUpload)
    {
        $this->filesUpload = $filesUpload;
        foreach ($this->filesUpload as $key => $value) {
            $this->filesUpload[$key]['message code'] =
                self::ERROR_FILE_UPLOAD[$value['error']];
        }
    }

    /**
     * @param bool $empty
     * @return array
     */
    public function fileControl(bool $empty): array
    {
        if ($empty) {
            $this->filesUpload = $this->removeEmptyArray();
        }

        if (!empty($this->filesUpload)) {
            $this->getControlErrorMessage();
            $this->testFile();
        }

        if (empty($this->codeError)) {
            $this->setFileName();
            return $this->filesUpload;
        }
        return $this->codeError;
    }

    /**
     * @return array
     */
    private function removeEmptyArray(): array
    {
        foreach ($this->filesUpload as $key => $code) {
            if ($code['error'] === 4) {
                unset($this->filesUpload[$key]);
            }
        }
        return $this->filesUpload;
    }

    /**
     * @return array
     */
    private function setFileName(): array
    {
        foreach ($this->filesUpload as $key => $value) {
            $extensionFailed = ltrim(strrchr($value['type'], '/'), '/');
            $this->filesUpload[$key]['name'] = uniqid('', true) . '.' . $extensionFailed;
        }
        return $this->filesUpload;
    }

    /**
     * @return void
     */
    private function getControlErrorMessage(): void
    {
        foreach ($this->filesUpload as $value) {
            if ($value['error'] !== 0) {
                $this->codeError[$value['name']]['error code'] = self::ERROR_FILE_UPLOAD[$value['error']];
            }
        }
    }

    /**
     *
     */
    private function testFile(): void
    {
        $size = self::SIZE_MAX / 1000000;
        foreach ($this->filesUpload as $file) {
            if (!in_array($file['type'], self::EXTENSIONS, true)) {
                $extensionFailed = ltrim(strrchr($file['type'], '/'), '/');
                $this->codeError[$file['name']]['extension'] = 'Extension "' . $extensionFailed . '" non valide';
            }
            if ($file['size'] > self::SIZE_MAX) {
                $this->codeError[$file['name']]['size'] = 'Le fichier doit faire moins de ' . $size . ' Mo';
            }
        }
    }

    /**
     * @param string $tmpName
     * @param string $rootPath
     * @param string $fileName
     */
    public function uploadFile(string $tmpName, string $rootPath, string $fileName)
    {
        move_uploaded_file($tmpName, $rootPath . $fileName);
    }
}
