<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class WalletIssetToUser implements Rule
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
        if(Wallet::where('created_by', Auth::user()->id)->where('id', $value)->firstOrFail()){
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
        return 'This wallet or user is not exist';
    }
}
