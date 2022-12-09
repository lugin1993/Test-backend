<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Exception;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required'
            ]);
            $content = file_get_contents($request->file('file'));
            $newFileName = time() . '-' . md5($content) . '.' .
                $request->file('file')->getClientOriginalExtension();
            DB::beginTransaction();
            $file = File::create([
                'name' => $newFileName,
                'original_name' => $request->file('file')->getClientOriginalName()
            ]);
            Storage::disk('local')->put($newFileName, $content);
            DB::commit();
            return response()->json($file);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(['message' => 'Не удалось загрузить файл'], 500);
        }
    }

    public function delete(File $file)
    {
        try {
            DB::beginTransaction();
            $file->delete();
            Storage::disk('local')->delete($file->name);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Файл был удален']);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(['message' => 'Не удалось удалить файл'], 500);
        }
    }

    public function download(File $file)
    {
        return response()->download(
            Storage::disk('local')->path($file->name),
            $file->original_name
        );
    }
}
