<?php

namespace App\Http\Requests;

use App\Exceptions\PostingTooOftenException;
use App\Reply;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateReply extends FormRequest
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
            'body' => ['required', new SpamFree]
        ];
    }

    /**
     * @throws \App\Exceptions\PostingTooOftenException
     */
    protected function failedAuthorization()
    {
        throw new PostingTooOftenException("You are posting too frequently");
    }
}
