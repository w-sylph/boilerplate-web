<?php

namespace App\Traits\Controllers;

use DB;
use Excel;

use App\Http\Requests\ImportRequest;

trait Importable
{	
	/* Return array with value of excel import class */
	abstract protected function importable(): array;
	// protected function importable(): array {
	// 	return [
	// 		'import' => ProductImport::class,
	// 	];
	// }

	/**
     * Execute excel import 
     * 
     * @param  Request $request
     */
    public function import(ImportRequest $request) 
    {   
        DB::beginTransaction();

        /**
         * Prevent strange character when exporting and importing.
         * Reference: https://github.com/Maatwebsite/Laravel-Excel/issues/1673#issuecomment-494050465
         */
        ob_end_clean(); ob_start();
        $importable = $this->importable();
        Excel::import($import = new $importable['import'], $request->file('import_file'));

        $errors = $import->errors;
        $error_count = count($errors);
        $success_count = $import->success;

        $success_label = $success_count > 1 ? 'items' : 'item';
        $error_label = $error_count > 1 ? 'items' : 'item';

        if ($success_count > 0 && $error_count < 1) {
            $message = "You have successfully imported all {$success_count} {$success_label}.";
        } else if ($success_count > 0 && $error_count > 0) {
            $message = "You have successfully imported {$success_count} {$error_label} but {$error_count} {$error_label} failed to be imported.";
        } else {
            $message = "All {$error_count} {$error_label} failed to be imported.";
        }

        DB::commit();

        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ]);
    }
}