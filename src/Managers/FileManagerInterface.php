<?php

namespace Fomvasss\FieldFile\Managers;

interface FileManagerInterface
{
    public function store($requestFile, array $attr = [], $returnModel = false);

    public function storeMultiple($requestFiles, array $attr = [], $returnModels = false): array;

    public function getPath($id): string;

    public function safeDelete($id): bool;

    public function delete($id): bool;

    public function deleteAllOldNonUsed(int $older = null): int;

    public function countUploadFilesByUserPerTime(int $userId, int $hours = null): int;
}
