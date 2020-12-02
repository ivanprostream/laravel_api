<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\GeneralService;


class WalletShowResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $convertTo = new GeneralService;
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'convertToUSD' => $convertTo->convertTo($this->amount, "USD"),
            'convertToBTC' => $convertTo->convertToBtc($this->amount, "USD")
        ];
    }
}
