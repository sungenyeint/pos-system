<?php

namespace App\Jobs\Admin;

use App\Exceptions\ImportException;
use App\Imports\ProductImport;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExcelImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file_path;

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    public function handle()
    {
        Log::setDefaultDriver('excel_import');

        logger()->info('Import Start');

        try {
            if (! Storage::exists($this->file_path)) {
                throw new ImportException($this->file_path . ' does not exist.');
            }

            $product_import = new ProductImport($this->file_path);
            $product_import->import();

        } catch (ImportException $ie) {
            logger()->info('$ie', [$ie->getCode(), $ie->getMessage()]);

        } catch (Exception $e) {
            logger()->error('$e', [$e->getCode(), $e->getMessage()]);
        }

        Storage::delete($this->file_path);

        logger()->info('Import End');
    }
}
