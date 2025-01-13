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
    private $import;

    public function __construct($import, $file_path)
    {
        $this->import = $import;
        $this->file_path = $file_path;
    }

    public function handle()
    {
        Log::setDefaultDriver('excel_import');

        logger()->info('Import Start', $this->import->toArray());

        $this->storeImport(2);

        $file_name = config('const.import_csv_file_path') . $this->import->file_name;

        try {
            // ファイル存在確認
            if (! Storage::exists($this->file_path)) {
                throw new ImportException($file_name . ' が存在していません。');
            }

            logger('-----------1---------');
            // Excel::import(new UserImport($this->import->id), $this->file_path);
            $product_import = new ProductImport($this->import);
            $product_import->import($file_name);

            $this->storeImport(3);

        } catch (ImportException $ie) {
            logger()->info('$ie', [$ie->getCode(), $ie->getMessage()]);
            $this->storeImport(10, [$ie->getMessage()]);

        } catch (Exception $e) {
            logger()->error('$e', [$e->getCode(), $e->getMessage()]);
            $this->storeImport(10);
        }

        Storage::delete($this->file_path);

        logger()->info('Import End', $this->import->toArray());
    }

    private function storeImport(int $status, array $messages = [])
    {
        $this->import->fill([
            'status' => $status,
            'messages' => $messages
        ])
        ->save();
    }
}