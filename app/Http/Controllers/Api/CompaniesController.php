<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateRequest;
use App\Http\Requests\Company\DeleteRequest;
use App\Http\Requests\Company\ShowRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $company = $request->user()->company[0]->id;

        $com = Company::find($company);

        return new CompanyResource($com);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        if ($request->user()->company()->exists()) {
            return response()->json(['message' => 'You can only create one company.'], 422);
        }

        $data = $request->safe();

        $company = new Company();

        DB::transaction(function () use ($data, &$company, $request) {

            $company->fill($data->except('address', 'phone'));

            $company->save();

            if ($request->has('address')) {
                $address = $request->input('address');
                $company->addressable()->create($address);
            }

            if ($request->has('phone')) {
                $phone = $request->input('phone');
                $company->phones()->create($phone);
            }

            $company->employees()->attach($request->user()->id, ['role_id' => Role::MANAGER->value]);
        });

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

        if ($request->has('address')) {
            $address = $request->input('address');
            $company->addressable()->update($address);
        }

        if ($request->has('phone')) {
            $phone = $request->input('phone');
            $company->phones()->update($phone);
        }

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
