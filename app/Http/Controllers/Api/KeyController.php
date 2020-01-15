<?php

namespace App\Http\Controllers\Api;

use App\Key;
use Illuminate\Http\Request;
use App\Http\Requests\KeyRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class KeyController extends Controller
{
    public function index()
    {
        return Key::with('keywords')->get();
    }

    public function show(Key $key)
    {
        $key->keywords;

        return $key;
    }

    public function create()
    {
        abort(403);
    }

    public function update(KeyRequest $request, Key $key)
    {
        $key->fill(request()->toArray());

        $key->save();

        return response()->json([
            'message' => 'Successfully updated keyword key.',
            'key' => $key
        ]);
    }

    public function destroy()
    {
        abort(403);
    }
}
