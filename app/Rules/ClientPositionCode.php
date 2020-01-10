<?php

namespace App\Rules;

use App\Client;
use App\ClientPosition;
use Illuminate\Contracts\Validation\Rule;

class ClientPositionCode implements Rule
{
    protected $position;
    protected $request;

    public function __construct(ClientPosition $position = NULL, $request)
    {
        $this->position = $position;
        $this->request = $request;
    }

    public function passes($attribute, $value)
    {
        $clientPosition = ClientPosition::where('code', $value)
                                ->where('client_id', $this->request->client->id)
                                ->where('id', '!=', $this->position->id ?? '')
                                ->first();

        return ($clientPosition == NULL) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The position :attribute already exists in this client.';
    }
}
