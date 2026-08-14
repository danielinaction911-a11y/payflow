<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait HandlesFileUploads
{
  /**
     * Upload a file into public/{folder}, optionally deleting an old file first.
     * Returns the relative path (e.g. "images/settings/abc123.jpg") ready for asset().
     *
     * @param UploadedFile $file       The uploaded file (from $request->file('field'))
     * @param string       $folder     Relative folder under public/, e.g. 'images/settings'
     * @param string|null  $oldPath    Existing relative path to delete, if replacing
     * @param string|null  $prefix     Optional filename prefix, e.g. the setting key
     */
    public function uploadFile(UploadedFile $file, string $folder, ?string $oldPath = null, ?string $prefix = null): string
    {
        $destination = public_path($folder);

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if ($oldPath) {
            $this->deleteFile($oldPath);
        }

        $filename = ($prefix ? $prefix . '_' : '') . uniqid() . '.' . $file->getClientOriginalExtension();

        try {
            $file->move($destination, $filename);
        } catch (\Throwable $e) {
            // Fallback for environments where move() can fail (permissions, Windows, temp handling)
            $source = method_exists($file, 'getRealPath') ? $file->getRealPath() : (method_exists($file, 'getPathname') ? $file->getPathname() : null);
            $target = rtrim($destination, '\\/') . DIRECTORY_SEPARATOR . $filename;

            if ($source && is_file($source)) {
                if (!@copy($source, $target)) {
                    throw $e;
                }
                @unlink($source);
                @chmod($target, 0644);
            } else {
                throw $e;
            }
        }

        return rtrim($folder, '/') . '/' . $filename;
    }

    /**
     * Update an existing file: uploads the new one and deletes the old one.
     * Just a semantic alias for uploadFile() with the old path passed —
     * kept separate so call sites read clearly (uploadFile vs updateFile).
     */
    public function updateFile(UploadedFile $file, string $folder, ?string $oldPath, ?string $prefix = null): string
    {
        return $this->uploadFile($file, $folder, $oldPath, $prefix);
    }

    /**
     * Delete a file given its relative path (e.g. "images/settings/abc.jpg").
     * Safe to call with null/empty — just no-ops.
     */
    public function deleteFile(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        $fullPath = public_path($path);

        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }
}
