<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactCollection;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function day()
    {
        $contacts = Contact::whereDate('created_at', Carbon::today())->get();

        return ContactCollection::collection($contacts);
    }

    public function week()
    {
        $contacts = Contact::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        return ContactCollection::collection($contacts);
    }

    public function month()
    {
        $contacts = Contact::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get();

        return ContactCollection::collection($contacts);
    }
}
