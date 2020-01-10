<?php

namespace App\Http\Controllers\Api;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::with('clients', 'clients.branches')->get();
    }

    public function store(Request $request)
    {
        abort(403);
    }

    public function show($id)
    {
        abort(403);
    }

    public function update(Request $request, $id)
    {
        abort(403);
    }

    public function destroy($id)
    {
        abort(403);
    }
}
