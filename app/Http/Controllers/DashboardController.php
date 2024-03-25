<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ReportController;

class DashboardController extends Controller
{
    public function index()
    {

        $report = app(ReportController::class)->admin_report();

        return Inertia::render('Dashboard', [
            'report' => $report,
        ]);
    }

    public function accounts(){
        $accounts = app(AccountController::class)->list();
        return response()->json($accounts);
    }
}
