<?php

namespace laravel\Http\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use laravel\Exceptions\ThrottleException;
use laravel\Reply;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamFree'
        ];
    }


    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws ThrottleException
     */
    protected function failedAuthorization()
    {
        throw new ThrottleException(
            'You are replying too frequently. Please take a break.'
        );
    }

}
