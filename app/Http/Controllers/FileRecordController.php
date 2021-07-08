<?php

namespace App\Http\Controllers;

use DB;
use Storage;
use Illuminate\Http\Request;

use App\Actions\Files\FileUploader;
use App\Models\Mutators\FileRecord;

class FileRecordController extends Controller
{
    public function upload(Request $request) {
        $files = [];
        $directory = 'images';

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $action = new FileUploader;
                $action->execute($file, $directory);
                $vars = $action->getAttributes();
                $vars['file_path'] = $action->getFilePath();

                $newFile = FileRecord::create($vars);
                $files[] = FileRecord::formatItem($newFile);
            }
        }

        return response()->json([
            'files' => $files
        ]);
    }

    public function remove(Request $request, $id) {
        DB::beginTransaction();

    	$filters = [
            'id' => $request->input('id'),
        ];

        $file = FileRecord::where($filters)->first();

        if ($file) {
            $file->delete();
        } else {
            abort(403, 'You are not authorized to delete the selected image.');
        }

        DB::commit();

        return response()->json(true);
    }

    public function removeTemp(Request $request) {
        DB::beginTransaction();

        $filters = [
            'id' => $request->input('id'),
        ];

        $file = FileRecord::where($filters)->whereNull('parent_id')->whereNull('parent_type')->first();

        if ($file) {
            $path = 'public/' . $file->file_path;

            if (Storage::exists($path)) {
                Storage::delete($path);
            }

            $file->delete();
        } else {
            abort(403, 'You are not authorized to delete the selected image.');
        }

        DB::commit();

        return response()->json(true);
    }
}
