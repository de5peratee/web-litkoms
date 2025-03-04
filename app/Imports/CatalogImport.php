<?php

namespace App\Imports;

use App\Models\Catalog;
use App\Models\Genre;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CatalogImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        // Создаем запись каталога
        $catalog = Catalog::create([
            'name' => $row['name'],
            'author' => $row['author'],
            'description' => $row['description'],
            'release_year' => (int) $row['year'], // Приводим к целому числу
            'cover' => null,
        ]);

        // Обрабатываем теги (genres)
        if (!empty($row['genres'])) {
            $tags = array_map('trim', explode(',', $row['genres']));
            foreach ($tags as $tag) {
                $genre = Genre::firstOrCreate(['name' => $tag]);
                $catalog->genres()->syncWithoutDetaching($genre->id);
            }
        }

        return $catalog;
    }

    public function prepareForValidation($data, $index)
    {
        $data = array_change_key_case($data, CASE_LOWER);
        $data = array_map('trim', $data);

        if (isset($data['year'])) {
            $data['year'] = (int) $data['year'];
        }

        return $data;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}