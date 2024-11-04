<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Faker\Factory as Faker;
class ProductControllerTest extends TestCase
{
    /**
     * Testa a validação de Input
     */
    public function testProductInsertValidationFailed(): void
    {
        //Payload ausente ou incompleto
        $payload = [];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . env("API_KEY"),
        ])->json('put', 'api/products/20221127',$payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /**
     * Testa a inserção com sucesso
     */
    public function testProductInsertSuccess(): void
    {

        $faker = Faker::create();

        $payload = [
            "code" => $faker->unique()->randomNumber(8),
            "status" => "published",
            "url" => $faker->url,
            "creator" => $faker->userName,
            "created_t" => $faker->unixTime,
            "last_modified_t" => $faker->unixTime,
            "product_name" => $faker->words(3, true),
            "quantity" => $faker->randomFloat(2, 100, 1000) . ' g',
            "brands" => $faker->company,
            "categories" => $faker->words(5, true),
            "labels" => "Contem gluten, Contém derivados de ovos, Contém ovos",
            "cities" => "",
            "purchase_places" => $faker->city . "," . $faker->country,
            "stores" => $faker->company,
            "ingredients_text" => $faker->sentence,
            "traces" => "Frutos de casca rija,Leite,Soja",
            "serving_size" => $faker->word . " " . $faker->randomFloat(2, 10, 50) . " g",
            "serving_quantity" => $faker->randomFloat(2, 10, 50),
            "nutriscore_score" => $faker->numberBetween(-10, 40),
            "nutriscore_grade" => "d",
            "main_category" => "en:" . $faker->word,
            "image_url" => $faker->imageUrl,
            "updated_at" => $faker->iso8601,
            "created_at" => $faker->iso8601,
            "id" => $faker->uuid,
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . env("API_KEY"),
        ])->json('put', "api/products/{$payload['code']}",$payload)
        ->assertStatus(Response::HTTP_ACCEPTED);

    }


    /**
     * Testa a deleção de um item
     */
    public function testProductDeleteSuccess(): void
    {
        $payload=$this->fakeObj();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . env("API_KEY"),
        ])->json('put', "api/products/{$payload['code']}",$payload)
        ->assertStatus(Response::HTTP_ACCEPTED);


        $this->withHeaders([
            'Authorization' => 'Bearer ' . env("API_KEY"),
        ])->json('delete', "api/products/{$payload['code']}")
        ->assertStatus(Response::HTTP_GONE);
    }


    private function fakeObj(){
        
        $faker = Faker::create();

        return [
            "code" => $faker->unique()->randomNumber(8),
            "status" => "published",
            "url" => $faker->url,
            "creator" => $faker->userName,
            "created_t" => $faker->unixTime,
            "last_modified_t" => $faker->unixTime,
            "product_name" => $faker->words(3, true),
            "quantity" => $faker->randomFloat(2, 100, 1000) . ' g',
            "brands" => $faker->company,
            "categories" => $faker->words(5, true),
            "labels" => "Contem gluten, Contém derivados de ovos, Contém ovos",
            "cities" => "",
            "purchase_places" => $faker->city . "," . $faker->country,
            "stores" => $faker->company,
            "ingredients_text" => $faker->sentence,
            "traces" => "Frutos de casca rija,Leite,Soja",
            "serving_size" => $faker->word . " " . $faker->randomFloat(2, 10, 50) . " g",
            "serving_quantity" => $faker->randomFloat(2, 10, 50),
            "nutriscore_score" => $faker->numberBetween(-10, 40),
            "nutriscore_grade" => "d",
            "main_category" => "en:" . $faker->word,
            "image_url" => $faker->imageUrl,
            "updated_at" => $faker->iso8601,
            "created_at" => $faker->iso8601,
            "id" => $faker->uuid,
        ];
    }

}
