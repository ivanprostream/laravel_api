<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;

class WalletIsset implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       if(Wallet::findOrFail($value)){
            return true;
       }
       return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This wallet is not exist';
    }
}
