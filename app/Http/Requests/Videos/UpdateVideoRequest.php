<?php

namespace App\Http\Requests\Videos;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'tag' => 'nullable',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'required',
            'level' => 'required',
            'duration' => 'required',
            'commentable' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title' . 'required',
            'tag' . 'nullable',
            'thumbnail' . 'nullable|image|max:2048',
            'category_id' . 'required',
            'level' . 'required',
            'duration' . 'required',
            'commentable' . 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(
                [
                    'error' => $errors,
                    'status_code' => 422,
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
