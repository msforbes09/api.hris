<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Module;
use App\Company;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;
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

        return Client::with('company')->get();
    }

    public function store(ClientRequest $request)
    {
        $this->authorize('create', $this->module);

        $client = Client::create(request()->only(Client::getModel()->getFillable()));

        Log::info(auth()->user()->username . ' has created a Client.', ['data' => $client]);

        return response()->json([
            'message' => 'Successfully created client.',
            'client' => $client
        ]);
    }

    public function show(Client $client)
    {
        $this->authorize('show', $this->module);

        return $client->load(['company']);
    }

    public function update(ClientRequest $request, Company $company, Client $client)
    {
        $this->authorize('update', $this->module);

        $client->update(request()->only($client->fillable));

        Log::info(auth()->user()->username . ' has updated a Client.', ['data' => $client]);

        return response()->json([
            'message' => 'Successfully updated client.',
            'client' => $client
        ]);
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $this->module);

        $client->delete();

        Log::info(auth()->user()->username . ' has deleted a Client.', ['data' => $client]);

        return [
            'message' => 'Successfully deleted client.'
        ];
    }

    public function restore($id)
    {
        $this->authorize('restore', $this->module);

        $client = Client::withTrashed()->findOrFail($id);

        $client->restore();

        Log::info(auth()->user()->username . ' has retored a Client.', ['data' => $client]);

        return [
            'message' => 'Successfully restored deleted client.'
        ];
    }
}
