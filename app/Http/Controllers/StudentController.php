<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user->usertype !== 'student') {
            abort(403, 'Unauthorized access.');
        }

        // Get student record with all relationships
        $student = Student::with([
            'department',
            'course',
            'company',
            'companySupervisor',
            'lecturer'
        ])->where('user_id', $user->id)->first();

        // Get student's activities (latest 5)
        $activities = [];
        $totalActivities = 0;
        $totalHours = 0;
        $approvedActivities = 0;
        $pendingActivities = 0;
        $canAddActivity = false;

        if ($student) {
            $activities = Activity::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            // Calculate statistics
            $totalActivities = Activity::where('student_id', $student->id)->count();
            $totalHours = Activity::where('student_id', $student->id)->sum('hours') ?? 0;
            $approvedActivities = Activity::where('student_id', $student->id)
                ->where('status', 'approved')
                ->count();
            $pendingActivities = Activity::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count();

            // Check if student can add activity today
            $canAddActivity = $this->canAddActivityToday($student->id);
        }

        // Calculate days completed (assuming 90 days total attachment period)
        $daysCompleted = 0;
        $totalDays = 90;

        if ($student && $activities->count() > 0) {
            $firstActivity = Activity::where('student_id', $student->id)
                ->orderBy('date', 'asc')
                ->first();

            if ($firstActivity) {
                $startDate = Carbon::parse($firstActivity->date);
                $today = Carbon::now();
                $daysCompleted = $startDate->diffInDays($today);

                // Cap at total days
                if ($daysCompleted > $totalDays) {
                    $daysCompleted = $totalDays;
                }
            }
        }

        // Pass all data to view
        return view('home.students.dashboard', compact(
            'student',
            'activities',
            'totalActivities',
            'totalHours',
            'approvedActivities',
            'pendingActivities',
            'daysCompleted',
            'totalDays',
            'canAddActivity'
        ));
    }

    // View student profile
    public function profile()
    {
        $user = Auth::user();
        $student = Student::with([
            'department',
            'course',
            'company',
            'companySupervisor',
            'lecturer'
        ])->where('user_id', $user->id)->first();

        return view('home.students.profile', compact('student'));
    }

    // View all activities
    public function activities()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $activities = [];
        $canAddActivity = false;

        if ($student) {
            $activities = Activity::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->paginate(15);

            // Check if student can add activity today
            $canAddActivity = $this->canAddActivityToday($student->id);
        }

        return view('home.students.activities', compact('activities', 'student', 'canAddActivity'));
    }

    // Add new activity
// Add new activity
    public function addActivity()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found. Please contact administrator.');
            return redirect()->back();
        }

        // Check if student can add activity today
        $canAddActivity = $this->canAddActivityToday($student->id);

        if (!$canAddActivity) {
            toastr()->closeButton()->warning('You have already logged an activity for today or today is a weekend.');
            return redirect()->route('student.activities');
        }

        return view('home.students.add_activity', compact('student', 'canAddActivity'));
    }
    // Store new activity
    public function storeActivity(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|min:10',
            'hours' => 'nullable|numeric|min:0|max:24',
        ]);

        // Check if the date is a weekday (Monday-Friday)
        $activityDate = Carbon::parse($request->date);
        if ($activityDate->isWeekend()) {
            toastr()->closeButton()->error('Activities can only be logged on weekdays (Monday to Friday).');
            return redirect()->back()->withInput();
        }

        // Check if student already has an activity for this date
        $existingActivity = Activity::where('student_id', $student->id)
            ->whereDate('date', $request->date)
            ->first();

        if ($existingActivity) {
            toastr()->closeButton()->error('You have already logged an activity for this date. You can only add one activity per day.');
            return redirect()->back()->withInput();
        }

        $activity = new Activity();
        $activity->student_id = $student->id;
        $activity->date = $request->date;
        $activity->description = $request->description;
        $activity->hours = $request->hours ?? 0;
        $activity->status = 'pending';
        $activity->save();

        toastr()->closeButton()->success('Activity logged successfully!');
        return redirect()->route('student.activities');
    }

    // Edit activity
    public function editActivity($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $activity = Activity::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        return view('home.students.edit_activity', compact('activity', 'student'));
    }

    // Update activity
    public function updateActivity(Request $request, $id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $activity = Activity::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|min:10',
            'hours' => 'nullable|numeric|min:0|max:24',
        ]);

        // Check if the date is a weekday (Monday-Friday)
        $activityDate = Carbon::parse($request->date);
        if ($activityDate->isWeekend()) {
            toastr()->closeButton()->error('Activities can only be logged on weekdays (Monday to Friday).');
            return redirect()->back()->withInput();
        }

        // Check if student already has an activity for this date (excluding current activity)
        $existingActivity = Activity::where('student_id', $student->id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $id)
            ->first();

        if ($existingActivity) {
            toastr()->closeButton()->error('You already have an activity logged for this date. You can only have one activity per day.');
            return redirect()->back()->withInput();
        }

        $activity->date = $request->date;
        $activity->description = $request->description;
        $activity->hours = $request->hours ?? 0;
        $activity->save();

        toastr()->closeButton()->success('Activity updated successfully!');
        return redirect()->route('student.activities');
    }

    // Delete activity
    public function deleteActivity($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $activity = Activity::where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $activity->delete();

        toastr()->closeButton()->success('Activity deleted successfully!');
        return redirect()->back();
    }

    // View reports
    public function reports()
    {
        $user = Auth::user();
        $student = Student::with([
            'department',
            'course',
            'company',
            'companySupervisor',
            'lecturer'
        ])->where('user_id', $user->id)->first();

        return view('home.students.reports', compact('student'));
    }

    // Upload report
    public function uploadReport(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $request->validate([
            'report' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Delete old report if exists
        if ($student->report) {
            Storage::disk('public')->delete($student->report);
        }

        // Store new report
        $reportPath = $request->file('report')->store('reports', 'public');
        $student->report = $reportPath;
        $student->save();

        toastr()->closeButton()->success('Report uploaded successfully!');
        return redirect()->back();
    }

    /**
     * Check if student can add activity for today
     * Returns true if today is a weekday and no activity exists for today
     */
    private function canAddActivityToday($studentId)
    {
        $today = Carbon::today();

        // Check if today is a weekend
        if ($today->isWeekend()) {
            return false;
        }

        // Check if activity already exists for today
        $existingActivity = Activity::where('student_id', $studentId)
            ->whereDate('date', $today)
            ->exists();

        return !$existingActivity;
    }
}
