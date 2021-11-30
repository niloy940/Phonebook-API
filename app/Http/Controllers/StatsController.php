<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactCollection;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function today()
    {
        $contacts = Contact::whereDate('created_at', Carbon::today())->count();

        return response()->json(['day' => 'today', 'count' => $contacts]);
    }

    public function week()
    {
        $contacts = Contact::select(
            DB::raw("(COUNT(*)) as count"),
            DB::raw("case cast (strftime('%w', created_at) as integer)
                when 0 then 'Sunday'
                when 1 then 'Monday'
                when 2 then 'Tuesday'
                when 3 then 'Wednesday'
                when 4 then 'Thursday'
                when 5 then 'Friday'
                else 'Saturday' end as day")
        )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereYear('created_at', date('Y'))
            ->groupBy('day')
            ->get();
        
        return $contacts;
    }

    public function month()
    {
        $contacts = Contact::select(
            DB::raw("(COUNT(*)) as count"),
            DB::raw("case cast (strftime('%m', created_at) as integer)
                when 1 then 'January'
                when 2 then 'February'
                when 3 then 'March'
                when 4 then 'April'
                when 5 then 'May'
                when 6 then 'June'
                when 7 then 'July'
                when 8 then 'August'
                when 9 then 'September'
                when 10 then 'Auctober'
                when 11 then 'November'
                else 'December' end as month")
        )
            
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        return $contacts;
    }
}
