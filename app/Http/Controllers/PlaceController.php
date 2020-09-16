<?php

namespace App\Http\Controllers;

use App\Entities\UserPlaceEntity;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PlaceController extends Controller
{
    public function create(Request $request) {
        try {
            $userId = $request->user()->id;
            DB::beginTransaction();
            /** @var Place $place */
            $place = Place::create($request->all());
            $place->users()->attach($place, ["user_id" => $userId]);
            DB::commit();
            $response = $this->createUserPlaceResponseFromPlace($place, $userId);
            return $response;
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'errors' => ['An error occurred']
            ], 500);
        }
    }

    public function update(Request $request) {
        try {
            $userId = $request->user()->id;
            Log::info($request);

            /** @var Place $place */
            $place = Place::find($request->input("id"));
            if($place != null) {
                DB::beginTransaction();
                $place->update(["name" => $request->input("name")]);
                $place->users()->updateExistingPivot($userId, ["notes" => $request->input("notes")]);
                DB::commit();
            }
            $response = $this->createUserPlaceResponseFromPlace($place, $userId);
            return $response;
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'errors' => ['An error occurred']
            ], 500);
        }
    }

    private function createUserPlaceResponseFromPlace($place, $userId) {
        $outcome = null;
        /** @var Place $place */
        if($place != null) {
            $placeWithPivot = $place->users()->where('user_id', $userId)->first();
            $outcome = new UserPlaceEntity();
            $outcome->id = $place->id;
            $outcome->name = $place->name;
            $outcome->latitude = $place->latitude;
            $outcome->longitude = $place->longitude;
            $outcome->isFavourite = boolval($placeWithPivot->pivot->is_favourite);
            $outcome->isWantToGo = boolval($placeWithPivot->pivot->is_want_to_go);
            $outcome->notes = $placeWithPivot->pivot->notes;

        }
        return response()->json($outcome, 201);
    }
}
