<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUploadRequest;
use App\Models\UserUpload;
use App\Services\AssetUploadService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserUploadController extends Controller
{
    // index
    public function index(): JsonResponse
    {
        // uploads
        $uploads = UserUpload::getUploads(Auth::user()->id)
            ->map(function ($upload) {
                $upload->formatted_created_at = Carbon::parse($upload->created_at)->diffForHumans();
                return $upload;
            })
            ->toArray();

        // return uploads
        return response()->json([
            'status'  => 'success',
            'uploads' => $uploads,
        ]);
    }

    public function upload(UserUploadRequest $request): JsonResponse
    {
        // file
        $file = $request->file('file');

        // check limit excedded
        if (hasExceededUploadLimit($file->getSize())) {
            return response()->json([
                'message' => 'You have reached your storage limit!',
            ], 403);
        }

        // filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // store document
        $path = app(AssetUploadService::class)->uploadAsset(
            $filename,
            $file,
            'document'
        );

        // Save record
        $user_upload            = new UserUpload();
        $user_upload->upload_id = uniqid();
        $user_upload->user_id   = Auth::user()->id;
        $user_upload->file_name = $file->getClientOriginalName();
        $user_upload->file_type = "document";
        $user_upload->file_url  = $path;
        $user_upload->file_size = $file->getSize();
        $user_upload->save();

        // formatted created at
        $user_upload->formatted_created_at = Carbon::parse($user_upload->created_at)->diffForHumans();

        // return response
        return response()->json([
            'status' => 'success',
            'upload' => $user_upload,
        ]);
    }

    // delete upload
    public function destroy(string $id): JsonResponse
    {
        $upload = UserUpload::where('upload_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Delete file from storage
        $path = str_replace('/storage/', '', $upload->file_url);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Delete database record
        $upload->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
