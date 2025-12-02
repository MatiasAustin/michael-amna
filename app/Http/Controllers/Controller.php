<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Resolve base path for public-facing files.
     * Override via PHOTO_PUBLIC_BASE in .env for shared hosting.
     */
    protected function publicBasePath(): string
    {
        $base = env('PHOTO_PUBLIC_BASE');

        return rtrim($base ?: public_path(), DIRECTORY_SEPARATOR);
    }

    /**
     * Build an absolute path from a relative public path fragment.
     */
    protected function absolutePublicPath(string $relativePath): string
    {
        return $this->publicBasePath() . DIRECTORY_SEPARATOR . ltrim($relativePath, '/\\');
    }
}
