<?php

namespace Database\Factories\Mutators;

use App\Models\Mutators\FileRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Helpers\GeneratorHelper;

class FileRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FileRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::random(40) . '.jpeg',
            'mime' => 'image/jpeg',
            'extension' => 'jpeg',
            'size' => 1024,
            'file_path' => GeneratorHelper::randomFile(),
        ];
    }
}
