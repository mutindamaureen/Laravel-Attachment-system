<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CompanySupervisor;
use App\Models\Activity;
use App\Models\SupervisorComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySupervisorController extends Controller
{
    // Get the current supervisor's record
    private function getCurrentSupervisor()
    {
        return CompanySupervisor::where('user_id', Auth::id())->first();
    }

    // ========== DASHBOARD ==========

    public function index()
    {
        $supervisor = $this->getCurrentSupervisor();

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor profile not found. Please contact admin.');
            return redirect('/');
        }

        // Get students allocated to this supervisor
        $students = Student::where('company_supervisor_id', $supervisor->id)
            ->with(['user', 'department', 'course', 'company', 'lecturer.user'])
            ->get();

        $totalStudents = $students->count();

        // Count total activities
        $totalActivities = Activity::whereHas('student', function($query) use ($supervisor) {
            $query->where('company_supervisor_id', $supervisor->id);
        })->count();

        // Count activities commented by supervisor
        $commentedActivities = SupervisorComment::where('supervisor_id', $supervisor->id)
            ->distinct('activity_id')
            ->count('activity_id');

        // Count pending activities
        $pendingActivities = Activity::whereHas('student', function($query) use ($supervisor) {
            $query->where('company_supervisor_id', $supervisor->id);
        })->where('status', 'pending')->count();

        return view('home.supervisor.dashboard', compact(
            'supervisor',
            'students',
            'totalStudents',
            'totalActivities',
            'commentedActivities',
            'pendingActivities'
        ));
    }

    // ========== STUDENT MANAGEMENT ==========

    public function view_students()
    {
        $supervisor = $this->getCurrentSupervisor();

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor profile not found.');
            return redirect()->back();
        }

        $students = Student::where('company_supervisor_id', $supervisor->id)
            ->with(['user', 'department', 'course', 'company', 'lecturer.user'])
            ->get();

        return view('home.supervisor.students.view_students', compact('students', 'supervisor'));
    }

    public function student_details($id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $student = Student::where('id', $id)
            ->where('company_supervisor_id', $supervisor->id)
            ->with([
                'user',
                'department',
                'course',
                'company',
                'lecturer.user',
                'activities' => function($query) {
                    $query->orderBy('date', 'desc');
                }
            ])
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found or not assigned to you.');
            return redirect()->back();
        }

        // Get supervisor comments for this student
        $supervisorComments = SupervisorComment::where('student_id', $student->id)
            ->where('supervisor_id', $supervisor->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total hours
        $totalHours = $student->activities->sum('hours');

        return view('home.supervisor.students.student_details', compact('student', 'supervisor', 'supervisorComments', 'totalHours'));
    }

    // ========== ACTIVITY MANAGEMENT ==========

    public function view_activities()
    {
        $supervisor = $this->getCurrentSupervisor();

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor profile not found.');
            return redirect()->back();
        }

        $activities = Activity::whereHas('student', function($query) use ($supervisor) {
            $query->where('company_supervisor_id', $supervisor->id);
        })
        ->with([
            'student.user',
            'supervisorComments' => function($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id);
            },
            'lecturerComments'
        ])
        ->orderBy('date', 'desc')
        ->get();

        return view('home.supervisor.activities.view_activities', compact('activities', 'supervisor'));
    }

    public function activity_details($id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $activity = Activity::whereHas('student', function($query) use ($supervisor) {
            $query->where('company_supervisor_id', $supervisor->id);
        })
        ->with([
            'student.user',
            'supervisorComments.supervisor.user',
            'lecturerComments.lecturer.user'
        ])
        ->find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found or not assigned to you.');
            return redirect()->back();
        }

        return view('home.supervisor.activities.activity_details', compact('activity', 'supervisor'));
    }

    // ========== COMMENTING ==========

    public function add_comment(Request $request, $activity_id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $activity = Activity::whereHas('student', function($query) use ($supervisor) {
            $query->where('company_supervisor_id', $supervisor->id);
        })->find($activity_id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $request->validate([
            'comment' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment = new SupervisorComment();
        $comment->activity_id = $activity_id;
        $comment->supervisor_id = $supervisor->id;
        $comment->student_id = $activity->student_id;
        $comment->comment = $request->comment;
        $comment->rating = $request->rating;
        $comment->save();

        toastr()->closeButton()->success('Comment added successfully.');
        return redirect()->back();
    }

    public function edit_comment($id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $comment = SupervisorComment::where('id', $id)
            ->where('supervisor_id', $supervisor->id)
            ->with('activity.student.user')
            ->first();

        if (!$comment) {
            toastr()->closeButton()->error('Comment not found.');
            return redirect()->back();
        }

        return view('home.supervisor.comments.edit_comment', compact('comment', 'supervisor'));
    }

    public function update_comment(Request $request, $id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $comment = SupervisorComment::where('id', $id)
            ->where('supervisor_id', $supervisor->id)
            ->first();

        if (!$comment) {
            toastr()->closeButton()->error('Comment not found.');
            return redirect()->back();
        }

        $request->validate([
            'comment' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment->comment = $request->comment;
        $comment->rating = $request->rating;
        $comment->save();

        toastr()->closeButton()->success('Comment updated successfully.');
        return redirect()->route('supervisor.activity.details', $comment->activity_id);
    }

    public function delete_comment($id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $comment = SupervisorComment::where('id', $id)
            ->where('supervisor_id', $supervisor->id)
            ->first();

        if (!$comment) {
            toastr()->closeButton()->error('Comment not found.');
            return redirect()->back();
        }

        $comment->delete();
        toastr()->closeButton()->success('Comment deleted successfully.');
        return redirect()->back();
    }

    // ========== REPORTS & EVALUATION ==========

    public function student_progress($student_id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $student = Student::where('id', $student_id)
            ->where('company_supervisor_id', $supervisor->id)
            ->with([
                'user',
                'department',
                'course',
                'company',
                'activities' => function($query) {
                    $query->orderBy('date', 'asc');
                }
            ])
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found.');
            return redirect()->back();
        }

        // Calculate statistics
        $totalHours = $student->activities->sum('hours');
        $completedActivities = $student->activities->where('status', 'approved')->count();
        $pendingActivities = $student->activities->where('status', 'pending')->count();
        $totalActivities = $student->activities->count();

        // Get all comments for this student
        $comments = SupervisorComment::where('student_id', $student_id)
            ->where('supervisor_id', $supervisor->id)
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate average rating
        $averageRating = $comments->whereNotNull('rating')->avg('rating');

        return view('home.supervisor.reports.student_progress', compact(
            'student',
            'supervisor',
            'totalHours',
            'completedActivities',
            'pendingActivities',
            'totalActivities',
            'comments',
            'averageRating'
        ));
    }

    public function submit_evaluation(Request $request, $student_id)
    {
        $supervisor = $this->getCurrentSupervisor();

        $student = Student::where('id', $student_id)
            ->where('company_supervisor_id', $supervisor->id)
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found.');
            return redirect()->back();
        }

        $request->validate([
            'supervisor_evaluation' => 'required|string',
            'performance_rating' => 'required|integer|min:1|max:5',
        ]);

        $student->supervisor_evaluation = $request->supervisor_evaluation;
        $student->performance_rating = $request->performance_rating;
        $student->evaluated_at = now();
        $student->save();

        toastr()->closeButton()->success('Evaluation submitted successfully.');
        return redirect()->back();
    }

    // ========== MY COMMENTS ==========

    public function my_comments()
    {
        $supervisor = $this->getCurrentSupervisor();

        $comments = SupervisorComment::where('supervisor_id', $supervisor->id)
            ->with(['activity.student.user', 'student.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.supervisor.comments.my_comments', compact('comments', 'supervisor'));
    }
}
