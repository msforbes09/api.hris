<?php

namespace App\Rules;

use App\Client;
use Illuminate\Contracts\Validation\Rule;

class ClientCompanyCode implements Rule
{
    protected $client;
    protected $request;

    public function __construct(Client $client = NULL, $request)
    {
        $this->client = $client;
        $this->request = $request;
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
        $clientCompany = Client::where('code', $value)
                                ->where('company_id', $this->request->company_id)
                                ->where('id', '!=', $this->client->id ?? '')
                                ->first();

        return ($clientCompany == NULL) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The client :attribute already exists in this company.';
    }
}
