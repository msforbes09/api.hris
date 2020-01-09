<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Company;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;

class ClientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        return Client::with('branches')->with('positions')->get();
    }

    public function store(ClientRequest $request)
    {
        $this->authorize('create', Client::class);

        $client = Client::create(request()->toArray());

        return response()->json([
            'message' => 'Successfully created client.',
            'client' => $client
        ]);
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        $client->branches;

        return $client;
    }

    public function update(ClientRequest $request, Company $company, Client $client)
    {
        $client->fill(request()->toArray());

        $client->save();

        return response()->json([
            'message' => 'Successfully updated client.',
            'client' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        abort(403);
    }
}
