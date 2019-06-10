<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateContestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'string|required',
            'description'=>'string|max:255',
            'problems'=>'array|required',
            'problems.*.id' => 'required|integer',
            'problems.*.point' => 'required|integer|min:0|max:3000',
            'start_time'=>'date|required',
            'end_time'=>'date|required|after:start_time',
            'penalty' => 'integer|required|min:0|max:100000',
        ];
    }
    /**
     * [Override] バリデーション失敗時
     *
     * @param Validator $validator
     * @throw HttpResponseException
     */
    protected function failedValidation( Validator $validator )
    {
        $response['data']    = [];
        $response['status']  = 'NG';
        $response['summary'] = 'Failed validation.';
        $response['errors']  = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );
    }
}
