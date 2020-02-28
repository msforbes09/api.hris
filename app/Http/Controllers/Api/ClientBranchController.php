<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Module;
use App\ClientBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientBranchRequest;

class ClientBranchController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'client')->first();
    }

    public function index(Client $client)
    {
        $this->authorize('allows', [$this->module, 'view']);

       return $client->branches()->orderBy('id', 'desc')->get();
    }

    public function store(ClientBranchRequest $request, Client $client, ClientBranch $branch)
    {
        $this->authorize('allows', [$this->module, 'create']);

        $branch = $client->branches()->create(request()->only($branch->getFillable()));

        Log::info(auth()->user()->username . ' has created a Client Branch.', ['data' => $branch]);

        return response()->json([
            'message' => 'Successfully created client branch.',
            'branch' => $branch
        ]);
    }

    public function show(Client $client, ClientBranch $branch)
    {
        $this->authorize('allows', [$this->module, 'show']);

        return $branch;
    }

    public function update(ClientBranchRequest $request, Client $client, ClientBranch $branch)
    {
        $this->authorize('allows', [$this->module, 'update']);

        $branch->update(request()->only($branch->getFillable()));

        Log::info(auth()->user()->username . ' has updated a Client Branch.', ['data' => $branch]);

        return response()->json([
            'message' => 'Successfuly updated client branch.',
            'branch' => $branch
        ]);
    }

    public function destroy(Client $client, ClientBranch $branch)
    {
        $this->authorize('allows', [$this->module, 'delete']);

        $branch->delete();

        Log::info(auth()->user()->username . ' has deleted a Client Branch.', ['data' => $branch]);

        return [
            'message' => 'Successfuly deleted client branch.'
        ];
    }

    public function restore($id)
    {
        $this->authorize('allows', [$this->module, 'restore']);

        $branch = ClientBranch::withTrashed()->findOrFail($id);

        $branch->restore();

        Log::info(auth()->user()->username . ' has restored a Client Branch.', ['data' => $branch]);

        return [
            'message' => 'Successfully restored deleted branch.'
        ];

    }
}
