<?php

namespace App\Http\Controllers;

use App\Models\member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @group Member 成員資料
 * @authenticated
 */
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $member = member::with('Tasks')->get();

        return response($member, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'group' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $member = new member;
        $member->name = $request->name;
        $member->group = $request->group;
        $member->save();

        return response()->json(['data saved'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(member $member)
    {
        //
        return response($member, Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, member $member)
    {
        //
        $member->update($request->all());
        return response($member, Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(member $member)
    {
        //
        $member->delete();
        return response()->json(['res deleted'], 204);

    }
}
