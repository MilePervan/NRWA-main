<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use App\Http\Resources\v1\ManagerResource;
use App\Http\Resources\v1\ManagerCollection;

/**
 * @OA\Tag(
 *     name="Managers",
 *     description="API Endpoints for Managers"
 * )
 */
class ManagerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/managers",
     *     tags={"Managers"},
     *     summary="Get list of managers",
     *     @OA\Parameter(name="search", in="query", description="Search by name", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of managers returned")
     * )
     */
    public function index(Request $request)
    {
        $query = Manager::with(['location', 'dispatchers']);
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        return new ManagerCollection($query->get());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/managers",
     *     tags={"Managers"},
     *     summary="Create a new manager",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","location_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="location_id", type="integer"),
     *             @OA\Property(property="dispatcher_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Manager created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|string',
            'location_id'=>'required|integer'
        ]);

        $manager = Manager::create($validated);
        if ($request->has('dispatcher_ids')) {
            $manager->dispatchers()->sync($request->dispatcher_ids);
        }
        return new ManagerResource($manager->load('dispatchers'));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/managers/{id}",
     *     tags={"Managers"},
     *     summary="Get a single manager",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Manager data returned"),
     *     @OA\Response(response=404, description="Manager not found")
     * )
     */
    public function show($id)
    {
        return new ManagerResource(Manager::with(['location','dispatchers'])->findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/managers/{id}",
     *     tags={"Managers"},
     *     summary="Update a manager",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="location_id", type="integer"),
     *             @OA\Property(property="dispatcher_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Manager updated successfully"),
     *     @OA\Response(response=404, description="Manager not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $manager = Manager::findOrFail($id);
        $validated = $request->validate([
            'name'=>'sometimes|required|string',
            'location_id'=>'sometimes|required|integer'
        ]);
        $manager->update($validated);

        if ($request->has('dispatcher_ids')) {
            $manager->dispatchers()->sync($request->dispatcher_ids);
        }
        return new ManagerResource($manager->load('dispatchers'));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/managers/{id}",
     *     tags={"Managers"},
     *     summary="Delete a manager",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Manager deleted successfully"),
     *     @OA\Response(response=404, description="Manager not found")
     * )
     */
    public function destroy($id)
    {
        $manager = Manager::findOrFail($id);
        $manager->delete();
        return response()->noContent();
    }
}

