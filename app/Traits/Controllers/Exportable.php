<?php

namespace App\Traits\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Helpers\StringHelper;
use Excel;
use Illuminate\Support\Str;

trait Exportable
{
	/* Return array eloquent to export, fetch controller and export classes */
	abstract protected function exportable(): array;
	// protected function exportable(): array {
	// 	return [
	// 		'controller' => SampleItemFetchController::class,
	// 		'model' => SampleItem::class,
	// 		'export' => SampleItemExport::class,
	// 	];
	// }

    /* Return export file name */
	protected function exportFileName() {
		return str_replace(' ', '-', strtolower(Str::slug(StringHelper::getShortClassName($this->exportable()['model']) . '_' . now()->toDateTimeString(), '-'))) . '.csv';
	}

    /* Export method query from fetch controller then export file */
	public function export(Request $request)
    {
    	$exportable = $this->exportable();
        $controller = new $exportable['controller'];
        $model = $exportable['model'];
        $export = $exportable['export'];

        $request = $request->merge(['ids_only' => 1]);

        $data = $controller->fetch($request);
        $ids = $data->getData()->items;
        
        $message = 'Exporting data, please wait...';

        if (!count($ids)) {
            throw ValidationException::withMessages([
                'items' => 'No items found.',
            ]);
        }

        if (!$request->ajax()) {
            $items = $model::whereIn('id', $ids)->get();

            /**
             * Prevent strange character when exporting and importing.
             * Reference: https://github.com/Maatwebsite/Laravel-Excel/issues/1673#issuecomment-494050465
             */
            ob_end_clean(); ob_start();
            return Excel::download(new $export($items), $this->exportFileName(), \Maatwebsite\Excel\Excel::CSV, [
			      'Content-Type' => 'text/csv',
			]);
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
            ]);
        }
    }
}