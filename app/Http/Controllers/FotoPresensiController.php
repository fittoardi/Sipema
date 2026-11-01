<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FotoPresensiController extends Controller
{
    /**
     * Stream private presensi selfie photo with authorization check.
     */
    public function show(Request $request, Presensi $presensi): BinaryFileResponse
    {
        $user = $request->user();

        // Authorization check: Admin, or the student who owns the photo, or the lecturer of the class
        $isOwner = $presensi->mahasiswa?->user_id === $user->id;
        $isLecturer = $presensi->pertemuan?->kelas?->dosen?->user_id === $user->id;
        $isAdmin = $user->isAdmin();

        if (! ($isOwner || $isLecturer || $isAdmin)) {
            abort(403, 'Anda tidak berwenang mengakses foto presensi ini.');
        }

        $path = $presensi->foto_selfie_path;
        if (! $path || ! Storage::disk('private')->exists($path)) {
            abort(404, 'File foto presensi tidak ditemukan.');
        }

        $fullDiskPath = Storage::disk('private')->path($path);

        return response()->file($fullDiskPath, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
        ]);
    }
}
