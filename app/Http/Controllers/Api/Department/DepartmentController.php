<?php

namespace App\Http\Controllers\Api\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Department\StoreDepartmentRequest;
use App\Http\Requests\Api\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        
        return DepartmentResource::collection(
            Department::withCount('users')->paginate()
        );
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        

        $department = Department::create($request->validated());

        return response()->json([
            'message' => 'Department created successfully',
            'department' => new DepartmentResource($department),
        ], Response::HTTP_CREATED);
    }

    public function show(Department $department): JsonResponse
    {
        

        return response()->json(
            new DepartmentResource($department->load('users'))
        );
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {

        $department->update($request->validated());

        return response()->json([
            'message' => 'Department updated successfully',
            'department' => new DepartmentResource($department),
        ]);
    }

    public function destroy(Department $department): JsonResponse
    {

        $department->delete();

        return response()->json([
            'message' => 'Department deleted successfully'
        ]);
    }
}