<?php

namespace App\Services\File;

use App\Exceptions\FileNotFoundException;
use App\Models\File;
use App\Services\File\DTO\FileValidationOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    private string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default', 'public');
    }

    /**
     * Сохранить файл
     *
     * @param UploadedFile $file Загруженный файл
     * @param string $folder Папка для сохранения (относительно корня диска)
     * @param FileValidationOptions|null $options Опции валидации
     * @return File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(
        UploadedFile $file,
        string $folder = 'uploads',
        ?FileValidationOptions $options = null
    ): File {
        // Валидация
        $this->validateFile($file, $options);

        // Генерация уникального имени файла
        $originalName = $file->getClientOriginalName();
        $filename = $this->generateUniqueFilename($originalName);
        $filePath = $this->buildPath($folder, $filename);

        // Сохранение файла
        $file->storeAs($folder, $filename, $this->disk);

        // Создание записи в БД
        return File::create([
            'filename' => $originalName,
            'file_path' => $filePath,
            'file_size_bytes' => $file->getSize(),
            'content_type' => $file->getMimeType(),
        ]);
    }

    /**
     * Обновить файл - заменить существующий
     *
     * @param int $fileId ID существующего файла
     * @param UploadedFile $newFile Новый файл
     * @param FileValidationOptions|null $options Опции валидации
     * @return File
     * @throws FileNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(
        int $fileId,
        UploadedFile $newFile,
        ?FileValidationOptions $options = null
    ): File {
        $existingFile = File::find($fileId);

        if (!$existingFile) {
            throw FileNotFoundException::byId($fileId);
        }

        // Валидация нового файла
        $this->validateFile($newFile, $options);

        // Удаление старого файла
        $this->deletePhysicalFile($existingFile->file_path);

        // Получение папки из старого пути
        $folder = $this->extractFolderFromPath($existingFile->file_path);

        // Генерация нового имени файла
        $originalName = $newFile->getClientOriginalName();
        $filename = $this->generateUniqueFilename($originalName);
        $filePath = $this->buildPath($folder, $filename);

        // Сохранение нового файла
        $newFile->storeAs($folder, $filename, $this->disk);

        // Обновление записи в БД
        $existingFile->update([
            'filename' => $originalName,
            'file_path' => $filePath,
            'file_size_bytes' => $newFile->getSize(),
            'content_type' => $newFile->getMimeType(),
        ]);

        return $existingFile->fresh();
    }

    /**
     * Обновить файл, создав новую запись (старый файл остаётся)
     *
     * @param UploadedFile $newFile Новый файл
     * @param string $folder Папка для сохранения
     * @param FileValidationOptions|null $options Опции валидации
     * @return File
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateWithNew(
        UploadedFile $newFile,
        string $folder = 'uploads',
        ?FileValidationOptions $options = null
    ): File {
        return $this->save($newFile, $folder, $options);
    }

    /**
     * Удалить файл
     *
     * @param int $fileId ID файла для удаления
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete(int $fileId): bool
    {
        $file = File::find($fileId);

        if (!$file) {
            throw FileNotFoundException::byId($fileId);
        }

        // Удаление физического файла
        $this->deletePhysicalFile($file->file_path);

        // Удаление записи из БД
        return $file->delete();
    }

    /**
     * Удалить файл по модели
     *
     * @param File $file Модель файла для удаления
     * @return bool
     */
    public function deleteByModel(File $file): bool
    {
        // Удаление физического файла
        $this->deletePhysicalFile($file->file_path);

        // Удаление записи из БД
        return $file->delete();
    }

    /**
     * Получить публичный URL файла
     *
     * @param File $file
     * @return string
     */
    public function getUrl(File $file): string
    {
        return Storage::disk($this->disk)->url($file->file_path);
    }

    /**
     * Получить публичный URL по пути
     *
     * @param string $filePath
     * @return string
     */
    public function getUrlByPath(string $filePath): string
    {
        return Storage::disk($this->disk)->url($filePath);
    }

    /**
     * Проверить существование файла
     *
     * @param File $file
     * @return bool
     */
    public function exists(File $file): bool
    {
        return Storage::disk($this->disk)->exists($file->file_path);
    }

    /**
     * Валидация файла
     *
     * @param UploadedFile $file
     * @param FileValidationOptions|null $options
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateFile(UploadedFile $file, ?FileValidationOptions $options = null): void
    {
        if ($options === null) {
            return;
        }

        $rules = [];
        $messages = $this->getValidationMessages();

        // Проверка размера
        if ($options->hasSizeRestriction()) {
            $maxSizeKB = $options->getMaxSizeMB() * 1024;
            $rules[] = "max:{$maxSizeKB}";
        }

        // Проверка расширения
        if ($options->hasExtensionRestriction()) {
            $rules[] = 'mimes:' . implode(',', $options->getAllowedExtensions());
        }

        // Проверка MIME типа
        if ($options->hasMimeTypeRestriction()) {
            // Для MIME типов используем кастомную валидацию
            if (!in_array($file->getMimeType(), $options->getAllowedMimeTypes())) {
                validator()->make(
                    ['file' => $file],
                    [],
                    $messages
                )->errors()->add('file', __('validation.invalid_file_type'));
            }
        }

        if (!empty($rules)) {
            validator()->make(
                ['file' => $file],
                ['file' => implode('|', $rules)],
                $messages
            )->validate();
        }
    }

    /**
     * Получить сообщения валидации
     *
     * @return array
     */
    protected function getValidationMessages(): array
    {
        return [
            'file.max' => __('validation.file_too_large', ['max' => ':max']),
            'file.mimes' => __('validation.invalid_file_extension'),
        ];
    }

    /**
     * Сгенерировать уникальное имя файла
     *
     * @param string $originalName
     * @return string
     */
    protected function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);

        // Транслитерация и очистка имени
        $basename = Str::slug($basename, '');
        $basename = substr($basename, 0, 50); // Ограничение длины

        $uniquePart = Str::random(8);

        return $basename . '_' . $uniquePart . '.' . $extension;
    }

    /**
     * Построить путь к файлу
     *
     * @param string $folder
     * @param string $filename
     * @return string
     */
    protected function buildPath(string $folder, string $filename): string
    {
        return str_replace('\\', '/', trim($folder, '/') . '/' . $filename);
    }

    /**
     * Извлечь папку из пути к файлу
     *
     * @param string $filePath
     * @return string
     */
    protected function extractFolderFromPath(string $filePath): string
    {
        return dirname($filePath);
    }

    /**
     * Удалить физический файл
     *
     * @param string $filePath
     * @return void
     */
    protected function deletePhysicalFile(string $filePath): void
    {
        if (Storage::disk($this->disk)->exists($filePath)) {
            Storage::disk($this->disk)->delete($filePath);
        }
    }

    /**
     * Установить диск для работы с файлами
     *
     * @param string $disk
     * @return $this
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Получить текущий диск
     *
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }
}
