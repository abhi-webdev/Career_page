<?php

namespace App\Http\Controllers;

use App\Models\Offer;

class AdminController extends Controller
{
    public function dashboard()
    {
        $offerPending = Offer::where(
            'status',
            'sent'
        )->count();

        $offerAccepted = Offer::where(
            'status',
            'accepted'
        )->count();

        $offerDeclined = Offer::where(
            'status',
            'declined'
        )->count();

        return view(
            'admin.dashboard',
            compact(
                'offerPending',
                'offerAccepted',
                'offerDeclined'
            )
        );
    }
}