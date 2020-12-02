<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;


class WalletLimitTen implements Rule
{
    protected $user;
    private $max_count;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max_count = 10)
    {
        $this->max_count = $max_count;
        $this->user = Auth::user()->id;
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
        if(Wallet::where('created_by', $this->user)->count() < $this->max_count){
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
        return 'You may save only '.$this->max_count.' wallets';
    }
}
