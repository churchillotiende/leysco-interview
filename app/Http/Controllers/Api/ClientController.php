<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Handle the request to fetch clients' history
    public function index()
    {        
        $clientsHistory = User::query()
            ->whereNull('role_id')
            ->get();                

        // Return the data as JSON response
        return response()->json($clientsHistory);
    }
}
