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
        $generalService = resolve(GeneralService::class);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'convertToUSD' => $generalService->convertTo($this->amount, "USD"),
            'convertToBTC' => $generalService->convertToBtc($this->amount, "USD")
        ];
    }
}
