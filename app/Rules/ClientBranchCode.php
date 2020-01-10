<?php

namespace App\Rules;

use App\ClientBranch;
use Illuminate\Contracts\Validation\Rule;

class ClientBranchCode implements Rule
{
    protected $branch;
    protected $request;

    public function __construct(ClientBranch $branch = NULL, $request)
    {
        $this->branch = $branch;
        $this->request = $request;
    }

    public function passes($attribute, $value)
    {
        $clientBranch = ClientBranch::where('code', $value)
                                ->where('client_id', $this->request->client->id)
                                ->where('id', '!=', $this->branch->id ?? '')
                                ->first();

        return ($clientBranch == NULL) ? true : false;
    }

    public function message()
    {
        return 'The branch :attribute already exists in this client.';
    }
}
