<?php

namespace App\Http\Controllers\Travels;

use App\Notifications\TravelRequestStatusNotification;
use Illuminate\Validation\Rules\Enum;
use App\Domain\TravelRequest\Enums\TravelStatus;
use App\Http\Controllers\Controller;
use App\Models\TravelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TravelRequest::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->only('destination', 'departure_date', 'return_date', 'status', 'user_id');

        $validated = Validator::make($data, [
            'destination' => 'required',
            'departure_date' => 'date',
            'return_date' => 'date',
            'status' => ['required', new Enum(TravelStatus::class)],
            'user_id' => 'exists:users,id'
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        TravelRequest::create([
            'destination' => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date' => $data['return_date'],
            'status' => $data['status'],
            'user_id' => $data['user_id']
        ]);

        return response()->json(['message' => 'Request submitted successfully.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = TravelRequest::find($id);

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = $request->only('destination', 'departure_date', 'return_date', 'status', 'user_id');

        $travel = TravelRequest::findOrFail($id);

        if($request->isMethod('put')) {
            $validated = Validator::make($data, [
                'destination' => 'required',
                'departure_date' => 'date',
                'return_date' => 'date',
                'status' => ['required', new Enum(TravelStatus::class)],
                'user_id' => 'exists:users,id'
            ]);

            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }


            $travel->update([
                'destination' => $data['destination'],
                'departure_date' => $data['departure_date'],
                'return_date' => $data['return_date'],
                'status' => $data['status'],
                'user_id' => $data['user_id'],
            ]);
        } elseif ($request->isMethod('patch')) {
            $validated = Validator::make($data, [
                'status' => ['required', new Enum(TravelStatus::class)],
            ]);

            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }

            $travel->update([
                'status' => $data['status'],
            ]);
        }

        if (in_array($data['status'], [TravelStatus::APROVADO->value, TravelStatus::CANCELADO->value])) {
            $travel->user->notify(new TravelRequestStatusNotification($travel));
        }

        return response()->json(['message' => 'Request updated successfully.', 'data' => $travel], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        TravelRequest::destroy($id);

        return response()->json(['message' => 'Request deleted successfully.'], 200);
    }
}
