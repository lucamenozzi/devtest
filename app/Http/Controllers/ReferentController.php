<?php

namespace App\Http\Controllers;

use App\Models\Referent;
use Illuminate\Http\Request;

class ReferentController extends Controller
{
    public function searchReferent(Request $request): \Illuminate\Http\JsonResponse
    {
        $referentToFind = $request->get('search');
        $referents = Referent::select('id', 'name', 'last_name', 'email', 'phone')
            ->where('name', 'like', "%{$referentToFind}%")
            ->orWhere('last_name', 'like', "%{$referentToFind}%")
            ->get();

        return response()->json($referents->unique('last_name'));
    }
}
