<?php

namespace App\Services\File\DTO;

class FileValidationOptions
{
    /**
     * @var array<string>|null
     */
    private ?array $allowedExtensions;

    private ?int $maxSizeMB;

    /**
     * @var array<string>|null
     */
    private ?array $allowedMimeTypes;

    /**
     * @param array<string>|null $allowedExtensions
     * @param array<string>|null $allowedMimeTypes
     */
    public function __construct(
        ?array $allowedExtensions = null,
        ?int $maxSizeMB = null,
        ?array $allowedMimeTypes = null
    ) {
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSizeMB = $maxSizeMB;
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * Создать опции для изображений
     */
    public static function images(int $maxSizeMB = 10): self
    {
        return new self(
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            maxSizeMB: $maxSizeMB,
            allowedMimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']
        );
    }

    /**
     * Создать опции для документов
     */
    public static function documents(int $maxSizeMB = 20): self
    {
        return new self(
            allowedExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'],
            maxSizeMB: $maxSizeMB,
            allowedMimeTypes: [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain'
            ]
        );
    }

    /**
     * Создать опции для всех типов файлов
     */
    public static function any(int $maxSizeMB = 50): self
    {
        return new self(
            allowedExtensions: null,
            maxSizeMB: $maxSizeMB,
            allowedMimeTypes: null
        );
    }

    /**
     * @return array<string>|null
     */
    public function getAllowedExtensions(): ?array
    {
        return $this->allowedExtensions;
    }

    public function getMaxSizeMB(): ?int
    {
        return $this->maxSizeMB;
    }

    public function getMaxSizeBytes(): ?int
    {
        return $this->maxSizeMB !== null ? $this->maxSizeMB * 1024 * 1024 : null;
    }

    /**
     * @return array<string>|null
     */
    public function getAllowedMimeTypes(): ?array
    {
        return $this->allowedMimeTypes;
    }

    public function hasExtensionRestriction(): bool
    {
        return $this->allowedExtensions !== null;
    }

    public function hasSizeRestriction(): bool
    {
        return $this->maxSizeMB !== null;
    }

    public function hasMimeTypeRestriction(): bool
    {
        return $this->allowedMimeTypes !== null;
    }
}
