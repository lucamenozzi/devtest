<?php

namespace App\Http\Controllers;

use App\Models\Referent;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ReferentController extends Controller
{
    public function searchReferent(Request $request): \Illuminate\Http\JsonResponse
    {
        $referentToFind = $request->get('search');
        $shipmentId = $request->get('shipmentId');
        $scope = $request->get('scope');
        //$teamId = Shipment::find($shipmentId)->team_id;

        $referents = Referent::where(function($query) use ($referentToFind) {
            $query->where('name', 'like', "%{$referentToFind}%")
                ->orWhere('last_name', 'like', "%{$referentToFind}%");
        })
           /* ->whereHas('teams', function ($query) use ($teamId) {
                $query->where('team_id', $teamId);
            })*/
            ->whereNotIn('id', function ($query) use ($shipmentId, $scope) {
                $query->select('referent_id')
                    ->from('referent_shipment')
                    ->where('shipment_id', $shipmentId)
                    ->where('scope', $scope);
            })
            ->get();

        return response()->json($referents);
    }
}
