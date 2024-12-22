<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait CanUploadFile
{
    public function uploadFile(?UploadedFile $file = null, $image = null, $path = null)
    {
        if ($file === null) {
            return $image;
        }

        $fileName = md5(time()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $fileName);
        Storage::disk('public')->delete("$path/$image");
        return $fileName;
    }

    public function deleteFile($path = null, $image = null): bool
    {
        if ($image) {
            Storage::disk('public')->delete("$path/$image");
        }

        return true;
    }
}
