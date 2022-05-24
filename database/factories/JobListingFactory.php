<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tags   = ['Laravel', 'API', 'Backend', 'Vue', 'React', 'WordPress', 'Development'];
        return [
            'user_id'     => User::all()->random()->id,
            'title'       => $this->faker->sentence(),
            'logo'        => 'https://picsum.photos/300?random='.rand(1, 500),
            'tags'        => $tags[rand(0, 6)],
            'company'     => $this->faker->company(),
            'email'       => $this->faker->companyEmail(),
            'location'    => $this->faker->city(),
            'website'     => $this->faker->url(),
            'description' => $this->faker->paragraph(5),
        ];
    }
}
