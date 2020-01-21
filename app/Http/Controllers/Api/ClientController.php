<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Module;
use App\Company;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;

class ClientController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code'. 'client');
    }

    public function index()
    {
        $this->authorize('view', $this->module);

        return Client::with('company')->with('branches')->with('positions')->get();
    }

    public function store(ClientRequest $request)
    {
        $this->authorize('create', $this->module);

        $client = Client::create(request()->toArray());

        return response()->json([
            'message' => 'Successfully created client.',
            'client' => $client
        ]);
    }

    public function show(Client $client)
    {
        $this->authorize('show', $this->module);

        $client->company;

        $client->branches;

        $client->positions;

        return $client;
    }

    public function update(ClientRequest $request, Company $company, Client $client)
    {
        $this->authorize('update', $this->module);

        $client->fill(request()->toArray());

        $client->save();

        return response()->json([
            'message' => 'Successfully updated client.',
            'client' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $this->module);

        $client->delete();

        return [
            'message' => 'Successfully deleted client.'
        ];
    }
}
