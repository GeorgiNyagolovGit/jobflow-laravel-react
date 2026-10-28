<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $user = $request->user();
        $file = $data['cv'];
        $path = $file->store("cvs/{$user->id}", 'local');

        if ($user->cv_path) {
            Storage::disk('local')->delete($user->cv_path);
        }

        $user->update([
            'cv_path' => $path,
            'cv_original_name' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'message' => 'CV uploaded successfully.',
            'cv' => [
                'name' => $user->cv_original_name,
                'download_url' => url('/api/profile/cv'),
            ],
        ], 201);
    }

    public function download(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user->cv_path && Storage::disk('local')->exists($user->cv_path),
            404,
            'CV not found.'
        );

        return Storage::disk('local')->download($user->cv_path, $user->cv_original_name);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->cv_path) {
            Storage::disk('local')->delete($user->cv_path);
        }

        $user->update([
            'cv_path' => null,
            'cv_original_name' => null,
        ]);

        return response()->json([
            'message' => 'CV deleted successfully.',
        ]);
    }
}
