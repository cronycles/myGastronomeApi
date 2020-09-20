<?php

namespace App\Http\Controllers;

use App\Entities\RatingEntity;
use App\Entities\UserPlaceEntity;
use App\Place;
use App\Rating;
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
            /** @var Place $place */
            $place = Place::find($request->input("id"));
            if($place != null) {
                DB::beginTransaction();
                $place->update(["name" => $request->input("name")]);
                $place->users()->updateExistingPivot($userId, [
                    "notes" => $request->input("notes"),
                    "is_favourite" => $request->input("isFavourite"),
                    "is_want_to_go" => $request->input("isWantToGo"),
                ]);
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

    public function review(Request $request) {
        try {
            $userId = $request->user()->id;
            $rating = null;
            if($request != null) {
                $rating = $request->input("rating");
            }
            if($rating != null) {
                /** @var Place $place */
                $place = Place::find($request->input("id"));
                if($place != null) {
                    DB::beginTransaction();
                    $rating = Rating::create([
                        "location" => $rating["location"],
                        "food" => $rating["food"],
                        "service" => $rating["service"],
                        "value_of_money" => $rating["valueOfMoney"],
                    ]);
                    $place->users()->updateExistingPivot($userId, [
                        "rating_id" => $rating->id,
                    ]);
                    DB::commit();
                }
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
            Log::info($placeWithPivot);
            if($placeWithPivot->pivot->rating_id != null){
                $dbRating = Rating::find($placeWithPivot->pivot->rating_id);
                if($dbRating != null) {
                    $outcome->rating = new RatingEntity();
                    $outcome->rating->location = $dbRating->location;
                    $outcome->rating->food = $dbRating->food;
                    $outcome->rating->service = $dbRating->service;
                    $outcome->rating->valueOfMoney = $dbRating->value_of_money;
                }
            }

        }
        return response()->json($outcome, 201);
    }
}
