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
            'code' => 'required|string',
            'url' => 'required|url',
            'creator' => 'required|string',
            'created_t' => 'required|integer',
            'last_modified_t' => 'required|integer',
            'product_name' => 'nullable|string',
            'abbreviated_product_name' => 'nullable|string',
            'generic_name' => 'nullable|string',
            'quantity' => 'nullable|string',
            'packaging' => 'nullable|string',
            'brands' => 'nullable|string',
            'categories' => 'nullable|string',
            'labels' => 'nullable|string',
            'cities' => 'nullable|string',
            'purchase_places' => 'nullable|string',
            'stores' => 'nullable|string',
            'countries' => 'nullable|string',
            'ingredients_text' => 'nullable|string',
            'allergens' => 'nullable|string',
            'traces' => 'nullable|string',
            'serving_size' => 'nullable|string',
            'serving_quantity' => 'nullable|integer',
            'nutriscore_score' => 'nullable|integer',
            'nutriscore_grade' => 'nullable|string|in:a,b,c,d,e,f',
            'main_category' => 'nullable|string',
            'image_url' => 'nullable|url',
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
