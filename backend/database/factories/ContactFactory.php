<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $path = public_path('images/default-user.png');
        $defaultImage = base64_encode(file_get_contents($path));

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'cep' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'state' => $this->faker->word(),
            'neighborhood' => $this->faker->word(),
            'street_address' => $this->faker->streetAddress(),
            'house_number' => $this->faker->numberBetween(1, 99999),
            'complement' => $this->faker->randomLetter(),
            'picture' => $defaultImage
        ];
    }
}
