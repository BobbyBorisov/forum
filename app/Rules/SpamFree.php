<?php

namespace App\Rules;

use App\Exceptions\InvalidKeyWordException;
use App\Exceptions\KeyHeldDownException;
use App\Inspections\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            return !resolve(Spam::class)->detect(request('body'));
        } catch (InvalidKeyWordException $exception) {
            return false;
        } catch (KeyHeldDownException $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your reply :attribute is not spam free';
    }
}
