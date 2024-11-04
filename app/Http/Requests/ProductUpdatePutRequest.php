<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductUpdatePutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'url' => 'required',
            'creator'  => 'string|required',
            'created_t'  => 'required',
            'status' => 'required|in:draft,trash,published',
            'last_modified_t' => 'required',
            'product_name'  => 'string|required',
            'quantity' => 'string|required',
            'brands' => 'string|required',
            'categories' => 'string|required',
            'labels' => 'string|required',
            'cities' => 'string|nullable',
            'purchase_places' => 'string|required',
            'stores' => 'string|required',
            'ingredients_text' => 'required',
            'traces' => 'string|required',
            'serving_size' => 'string|required',
            'serving_quantity' => 'required',
            'nutriscore_score' => 'required',
            'nutriscore_grade' => 'string|required',
            'main_category' => 'string|required',
            'image_url' => 'string|required',
        ];
    }


   
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
    

}
