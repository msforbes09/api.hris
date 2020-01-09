<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\ClientPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPositionRequest;

class ClientPositionController extends Controller
{
    public function index(Client $client)
    {
        $this->authorize('viewAny', ClientPosition::class);

        return $client->positions;
    }

    public function store(ClientPositionRequest $request, Client $client)
    {
        $this->authorize('create', ClientPosition::class);

        $position = $client->positions()->create(request()->toArray());

        return response()->json([
            'message' => 'Successfully created client position.',
            'position' => $position
        ]);
    }

    public function show(Client $client, ClientPosition $position)
    {
        $this->authorize('view', $position);

        return $position;
    }

    public function update(ClientPositionRequest $request, Client $client, ClientPosition $position)
    {
        $this->authorize('update', $position);

        $position->fill(request()->toArray());

        return response()->json([
            'message' => 'Successfully updated client position.',
            'position' => $position
        ]);
    }

    public function destroy($id)
    {
        abort(403);
    }
}
