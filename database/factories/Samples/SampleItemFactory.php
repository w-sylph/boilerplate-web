<?php

namespace Database\Factories\Samples;

use App\Models\Samples\SampleItem;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Helpers\GeneratorHelper;

class SampleItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SampleItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(3),
            'description' => $this->faker->paragraph,
            'file_path' => GeneratorHelper::randomFile(),
            'date' => now(),
            'dates' => [now()->toDateString(), now()->addDays(2)->toDateString()],
            'zip_code' => rand(1000, 9999),
            'telephone_number' => GeneratorHelper::generateTelephoneNumber(),
            'mobile_number' => GeneratorHelper::generateMobileNumber(),
            'color' => '#000000'
        ];
    }
}
