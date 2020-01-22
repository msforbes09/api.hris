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
        $this->module = Module::where('code', 'client')->first();
    }

    public function index()
    {
        $this->authorize('view', $this->module);

        return Client::with('company')/*->with('branches')->with('positions')*/->get();
    }

    public function store(ClientRequest $request, Client $client)
    {
        $this->authorize('create', $this->module);

        $client = Client::create($request->only($client->fillable));

        return response()->json([
            'message' => 'Successfully created client.',
            'client' => $client
        ]);
    }

    public function show(Client $client)
    {
        $this->authorize('show', $this->module);

        $client->company;
        // $client->branches;
        // $client->positions;

        return $client;
    }

    public function update(ClientRequest $request, Company $company, Client $client)
    {
        $this->authorize('update', $this->module);

        $client->update($request->only($client->fillable));

        return response()->json([
            'message' => 'Successfully updated client.',
            'client' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $this->module);

        $client->branches()->delete();
        $client->positions()->delete();
        $client->delete();

        return [
            'message' => 'Successfully deleted client.'
        ];
    }

    public function restore($id)
    {
        $this->authorize('restore', $this->module);

        $client = Client::withTrashed()->findOrFail($id);

        $client->branches()->restore();
        $client->positions()->restore();
        $client->restore();

        return [
            'message' => 'Successfully restored deleted client.'
        ];
    }
}
