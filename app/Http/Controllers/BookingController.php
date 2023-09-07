<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function new(Request $request)
    {
        $existing_booking = \App\Models\Booking::where('date', $request->date)->where('user_id', $request->user()->id)->first();
        if ($existing_booking) {
            return response()->json(['id' => $existing_booking->id]);
        }
        $booking = new \App\Models\Booking();
        $booking->date = $request->date;
        $booking->products = json_encode($request->products);
        $booking->user_id = $request->user()->id;
        $booking->save();
        return response()->json(['id' => $booking->id]);
    }

    public function index(Request $request, $date)
    {
        //Get all bookings by date, append user username and email from the foreign key
        $bookings = \App\Models\Booking::where('date', $date)->get();
        foreach ($bookings as $booking) {
            $booking->user = \App\Models\User::find($booking->user_id);
        }
        return response()->json($bookings);
    }
}
