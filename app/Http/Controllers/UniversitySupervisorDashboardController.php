<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UniversitySupervisorDashboardController extends Controller
{
    public function index(Request $request)
{
    $events = [
        ['title' => 'Event 1', 'when' => 'Timeline'],
        ['title' => 'Event 2', 'when' => 'Timeline'],
        ['title' => 'Event 3', 'when' => 'Timeline'],
    ];

    $activities = [
        'Activity 1',
        'Activity 2',
    ];

    return view('dashboards.university', compact('events', 'activities'));
}
}