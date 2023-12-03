<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'category' => 'sometimes|exists:categories,id', 
            'source' => 'sometimes|string',
            'date' => 'sometimes|date',
            'q' => 'sometimes|string|max:255',
            'preferred_categories' => 'sometimes|array', // Validating as an array
            'preferred_categories.*' => 'exists:categories,id', // Each category in the array must exist
            'preferred_sources' => 'sometimes|array',
            'preferred_sources.*' => 'exists:sources,id',
            'preferred_authors' => 'sometimes|array', // If authors are stored as names
            'preferred_authors.*' => 'string' // If authors are stored as names
        ];
    }
}
