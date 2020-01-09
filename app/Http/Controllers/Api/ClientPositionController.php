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
        return $client->positions;
    }

    public function store(ClientPositionRequest $request, Client $client)
    {
        $position = $client->positions()->create(request()->toArray());

        return response()->json([
            'message' => 'Successfully created client position.',
            'position' => $position
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client, ClientPosition $position)
    {
        return $position;
    }

    public function update(ClientPositionRequest $request, Client $client, ClientPosition $position)
    {
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
