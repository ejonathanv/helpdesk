<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function admin_report(){
        $report = [
            'open_tickets' => DB::table('tickets')->where('status_id', 1)->count(),
            'resolved_tickets' => DB::table('tickets')->where('status_id', 2)->count(),
            'closed_tickets' => DB::table('tickets')->where('status_id', 6)->count(),
            'in_progress_tickets' => DB::table('tickets')->where('status_id', 3)->count(),
        ];

        return $report;
    }
}
