<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Dispatcher;
use Illuminate\Http\Request;
use App\Http\Resources\v1\DispatcherResource;
use App\Http\Resources\v1\DispatcherCollection;

/**
 * @OA\Tag(
 *     name="Dispatchers",
 *     description="API Endpoints for Dispatchers"
 * )
 */
class DispatcherController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/dispatchers",
     *     tags={"Dispatchers"},
     *     summary="Get list of dispatchers",
     *     @OA\Parameter(name="search", in="query", description="Search by name", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="List of dispatchers returned")
     * )
     */
    public function index(Request $request)
    {
        $query = Dispatcher::query();
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        return new DispatcherCollection($query->get());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/dispatchers",
     *     tags={"Dispatchers"},
     *     summary="Create a new dispatcher",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Dispatcher created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name'=>'required|string','phone'=>'required|string']);
        $dispatcher = Dispatcher::create($validated);
        return new DispatcherResource($dispatcher);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/dispatchers/{id}",
     *     tags={"Dispatchers"},
     *     summary="Get a single dispatcher",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Dispatcher data returned"),
     *     @OA\Response(response=404, description="Dispatcher not found")
     * )
     */
    public function show($id)
    {
        return new DispatcherResource(Dispatcher::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/dispatchers/{id}",
     *     tags={"Dispatchers"},
     *     summary="Update a dispatcher",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Dispatcher updated successfully"),
     *     @OA\Response(response=404, description="Dispatcher not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $dispatcher = Dispatcher::findOrFail($id);
        $dispatcher->update($request->only(['name', 'phone']));
        return new DispatcherResource($dispatcher);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/dispatchers/{id}",
     *     tags={"Dispatchers"},
     *     summary="Delete a dispatcher",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Dispatcher deleted successfully"),
     *     @OA\Response(response=404, description="Dispatcher not found")
     * )
     */
    public function destroy($id)
    {
        $dispatcher = Dispatcher::findOrFail($id);
        $dispatcher->delete();
        return response()->noContent();
    }
}
