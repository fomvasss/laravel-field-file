<?php

namespace Fomvasss\FieldFile\Managers;

use Fomvasss\FieldFile\Models\File;

/**
 * Class BaseManager
 *
 * @package Fomvasss\FieldFile\Managers
 */
class BaseFileManager implements FileManagerInterface
{
    protected $fileModel;

    protected $destinationPath;

    /**
     * BaseFileManager constructor.
     *
     * @param $fileModel
     */
    public function __construct(File $fileModel)
    {
        $this->fileModel = $fileModel;
    }

    /**
     * @param $requestFile
     * @param array $attr
     * @param bool $returnModel
     * @return mixed
     */
    public function store($requestFile, array $attr = [], $returnModel = false)
    {
        return 0;
    }

    /**
     * @param $requestFiles
     * @param array $attr
     * @param bool $returnModels
     * @return array
     */
    public function storeMultiple($requestFiles, array $attr = [], $returnModels = false): array
    {
        $result = [];
        foreach ($requestFiles as $requestFile) {
            $result[] = $this->store($requestFile, $attr, $returnModels);
        }

        return $result;
    }

    /**
     * @param $requestFile
     * @param array $attr
     * @return array
     */
    public function getFileAttributes($requestFile, array $attr): array
    {
        $userId = \Auth::id();
        $mimeType = $requestFile->getMimeType();
        $fileSize = $requestFile->getSize();
        $fileExtension = $requestFile->getClientOriginalExtension();
        $originalFileName = str_slug(rtrim($requestFile->getClientOriginalName(), '.'.$fileExtension));

        return [
            'path' => $attr['path'] ?? null,
            'type' => $attr['type'] ?? null,
            'extension' => $fileExtension,
            'original_name' => $originalFileName,
            'custom_file_name' => str_slug($attr['custom_file_name'] ?? ''),
            'mime_type' => $mimeType,
            'size' => $fileSize,
            'user_id' => $userId,
        ];
    }

    /**
     * @param array $fileAttributes[name, extension, mime_type, size, user_id]
     * @return string
     */
    protected function generateName(array $fileAttributes): string
    {
        $userId = $fileAttributes['user_id'] ?? '';

        if (!empty($fileAttributes['custom_file_name'])) {
            $fileName = $this->getUniqueFileName($fileAttributes['path'], $fileAttributes['custom_file_name'], $fileAttributes['extension']);
        } elseif (config('field-file.save_original_name')) {
            $fileName = $this->getUniqueFileName($fileAttributes['path'], $fileAttributes['original_name'], $fileAttributes['extension']);
        } else {
            $fileName = time() . '-' . str_random(5) . ($userId ? '-' . $userId : '');
        }

        return $fileName;
    }

    protected function getUniqueFileName($path, $name, $extension)
    {
        $uniqueName = $name;
        $i = 0;
        while (file_exists($path .'/'. $uniqueName .'.'. $extension)) {
            $uniqueName = $name .'-'. (++$i);
        }

        return $uniqueName;
    }

    /**
     * @param $fileDiskName
     * @param array $attribute
     * @return \Fomvasss\FieldFile\Models\File
     */
    protected function saveDb($fileDiskName, array $attribute = []): File
    {
        $model = $this->fileModel->create(array_merge(['name' => $fileDiskName], $attribute));

        return $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->fileModel->findOrFail($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getPath($id): string
    {
        $file = $this->fileModel->findOrFail($id);
        return ($file->path ?: $this->destinationPath) . '/' . $file->name;
    }

    /**
     * @param $id
     * @return bool
     */
    public function safeDelete($id): bool
    {
        if ($file = $this->fileModel->where('id', $id)->where('is_used', 1)->first()) {
            $file->update(['is_used' => 0]);
            return true;
        }

        return false;
    }

    /**
     * @param string $fulPathFileName
     * @return bool
     */
    public function deleteFile(string $fulPathFileName): bool
    {
        $fulPathFileName = public_path($fulPathFileName);

        if (file_exists($fulPathFileName)) {
            return unlink($fulPathFileName);
        } else {
            \Log::error(__METHOD__ . ' File not deleted, file not found from disk. Filename: ' . $fulPathFileName);
        }
        return false;
    }

    public function delete($id): bool
    {
        $file = $this->fileModel->find($id);
        if ($file) {
            $file->delete();
            $path = ($file->path ?: $this->destinationPath) .'/'. $file->name;
            $this->deleteFile($path) ?: \Log::error('File not deleted, file not found from disk. File path: '.$path);
            return true;
        }
        return false;
    }

    /**
     * @param int|null $older
     * @return int
     */
    public function deleteAllOldNonUsed(int $older = null): int
    {
        $hours = ($older > 0) ?: config('field-file.time_limit_old_file', 72);
        $date = \Carbon\Carbon::now()->addHour(-1*$hours);

        $files = $this->fileModel->where('updated_at', '<', $date)->where('is_used', 0)->get();

        $count = 0;

        foreach ($files as $file) {
            $file->delete();
            $path = ($file->path ?: $this->destinationPath) .'/'. $file->name;
            $this->deleteFile($path) ?: \Log::error('File not deleted, file not found from disk. File path: '.$path);
            $count++;
        }

        return $count;
    }

    /**
     * @param int $userId
     * @param int|null $hours
     * @return mixed
     */
    public function countUploadFilesByUserPerTime(int $userId, int $hours = null): int
    {
        $hours = ($hours > 0) ?: config('field-file.download_limit.hours', 24);
        $date = \Carbon\Carbon::now($hours)->addHour(-1 * $hours);

        return $this->fileModel->where('user_id', $userId)->where('created_at', '>', $date)->count();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function checkMakeDir(string $path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }
}
