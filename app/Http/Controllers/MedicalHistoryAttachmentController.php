<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use Illuminate\Support\Facades\Storage;

class MedicalHistoryAttachmentController extends Controller
{
    public function download(MedicalHistory $medicalHistory, string $filename)
    {
        $this->authorize('view', $medicalHistory);

        $attachments = $medicalHistory->attachments ?? [];
        $path = null;

        foreach ($attachments as $attachment) {
            if (basename($attachment) === $filename) {
                $path = $attachment;
                break;
            }
        }

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('local')->path($path);

        return response()->download($fullPath);
    }
}
