<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Resources\v1\LocationResource;
use App\Http\Resources\v1\LocationCollection;

/**
 * @OA\Tag(
 *     name="Locations",
 *     description="API Endpoints for Locations"
 * )
 */
class LocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/locations",
     *     tags={"Locations"},
     *     summary="Get list of locations",
     *     @OA\Parameter(name="search", in="query", description="Search by name", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of locations returned")
     * )
     */
    public function index(Request $request)
    {
        $query = Location::query();
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        return new LocationCollection($query->get());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/locations",
     *     tags={"Locations"},
     *     summary="Create a new location",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="address", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Location created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'sometimes|string|nullable'
        ]);
        $location = Location::create($validated);
        return new LocationResource($location);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/locations/{id}",
     *     tags={"Locations"},
     *     summary="Get a single location",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location data returned"),
     *     @OA\Response(response=404, description="Location not found")
     * )
     */
    public function show($id)
    {
        return new LocationResource(Location::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/locations/{id}",
     *     tags={"Locations"},
     *     summary="Update a location",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="address", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Location updated successfully"),
     *     @OA\Response(response=404, description="Location not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'address' => 'sometimes|string|nullable'
        ]);
        $location->update($validated);
        return new LocationResource($location);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/locations/{id}",
     *     tags={"Locations"},
     *     summary="Delete a location",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Location deleted successfully"),
     *     @OA\Response(response=404, description="Location not found")
     * )
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return response()->noContent();
    }
}
