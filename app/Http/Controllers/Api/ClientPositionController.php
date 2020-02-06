<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Module;
use App\ClientPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPositionRequest;

class ClientPositionController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'client')->first();
    }

    public function index(Client $client)
    {
        $this->authorize('view', $this->module);

        return $client->positions()->get();
    }

    public function store(ClientPositionRequest $request, Client $client, ClientPosition $position)
    {
        $this->authorize('create', $this->module);

        $position = $client->positions()->create(request()->only($position->getFillable()));

        Log::info(auth()->user()->username . ' has created a Client Position.', ['data' => $position]);

        return response()->json([
            'message' => 'Successfully created client position.',
            'position' => $position
        ]);
    }

    public function show(Client $client, ClientPosition $position)
    {
        $this->authorize('show', $this->module);

        return $position;
    }

    public function update(ClientPositionRequest $request, Client $client, ClientPosition $position)
    {
        $this->authorize('update', $this->module);

        $position->update($request->only($position->getFillable()));

        Log::info(auth()->user()->username . ' has created a Client Position.', ['data' => $position]);

        return response()->json([
            'message' => 'Successfully updated client position.',
            'position' => $position
        ]);
    }

    public function destroy(Client $client, ClientPosition $position)
    {
        $this->authorize('delete', $this->module);

        $position->delete();

        Log::info(auth()->user()->username . ' has deleted a Client Position.', ['data' => $client]);

        return [
            'message' => 'Successfully deleted client position.'
        ];
    }

    public function restore($id)
    {
        $this->authorize('restore', $this->module);

        $position = ClientPosition::withTrashed()->findOrFail($id);

        $position->restore();

        Log::info(auth()->user()->username . ' has restored a Client Position.', ['data' => $position]);

        return [
            'message' => 'Successfully restored deleted position.'
        ];

    }
}
