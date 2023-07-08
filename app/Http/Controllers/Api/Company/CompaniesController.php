<?php

namespace App\Http\Controllers\Api\Company;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateRequest;
use App\Http\Requests\Company\DeleteRequest;
use App\Http\Requests\Company\ShowRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $company->employees()->attach($request->user()->id, ['role_id' => Role::MANAGER->value]);

        return new CompanyResource($company);

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, ShowRequest $request)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $company->update($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, DeleteRequest $request)
    {
        $company->delete();

        return response()->noContent();
    }
}
