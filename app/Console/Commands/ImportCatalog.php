<?php

namespace App\Console\Commands;

use App\Imports\CatalogImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportCatalog extends Command
{
    protected $signature = 'catalog:import {file}';
    protected $description = 'Import catalog data from an Excel file';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("Файл {$file} не найден!");
            return 1;
        }

        try {
            Excel::import(new CatalogImport, $file);
            $this->info('Данные успешно импортированы!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Ошибка при импорте: ' . $e->getMessage());
            return 1;
        }
    }
}