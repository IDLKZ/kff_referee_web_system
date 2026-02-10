<?php

namespace App\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public static function byId(int $id): self
    {
        return new self("File with ID {$id} not found.");
    }

    public static function byPath(string $path): self
    {
        return new self("File at path {$path} not found.");
    }
}
