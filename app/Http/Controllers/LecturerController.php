<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Activity;
use App\Models\LecturerComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LecturerController extends Controller
{
    // Get the current lecturer's record
    private function getCurrentLecturer()
    {
        return Lecturer::where('user_id', Auth::id())->first();
    }

    // ========== DASHBOARD ==========

    public function index()
    {
        $lecturer = $this->getCurrentLecturer();

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer profile not found. Please contact admin.');
            return redirect('/');
        }

        // Get students allocated to this lecturer
        $students = Student::where('lecturer_id', $lecturer->id)
            ->with(['user', 'department', 'course', 'company', 'companySupervisor.user'])
            ->get();

        $totalStudents = $students->count();

        // Count activities pending review
        $pendingActivities = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })->where('status', 'pending')->count();

        // Count students who submitted reports
        $studentsWithReports = $students->filter(function($student) {
            return !empty($student->report);
        })->count();

        // Count graded students
        $gradedStudents = $students->filter(function($student) {
            return !empty($student->final_grade);
        })->count();

        return view('home.lecturers.dashboard', compact(
            'lecturer',
            'students',
            'totalStudents',
            'pendingActivities',
            'studentsWithReports',
            'gradedStudents'
        ));
    }

    // ========== STUDENT MANAGEMENT ==========

    public function view_students()
    {
        $lecturer = $this->getCurrentLecturer();

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer profile not found.');
            return redirect()->back();
        }

        $students = Student::where('lecturer_id', $lecturer->id)
            ->with(['user', 'department', 'course', 'company', 'companySupervisor.user'])
            ->get();

        return view('home.lecturers.students.view_students', compact('students', 'lecturer'));
    }

    public function student_details($id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $id)
            ->where('lecturer_id', $lecturer->id)
            ->with([
                'user',
                'department',
                'course',
                'company',
                'companySupervisor.user',
                'activities' => function($query) {
                    $query->orderBy('date', 'desc');
                }
            ])
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found or not assigned to you.');
            return redirect()->back();
        }

        // Get lecturer comments for this student
        $lecturerComments = LecturerComment::where('student_id', $student->id)
            ->where('lecturer_id', $lecturer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.lecturers.students.student_details', compact('student', 'lecturer', 'lecturerComments'));
    }

    // ========== ACTIVITY MANAGEMENT ==========

    public function view_activities()
    {
        $lecturer = $this->getCurrentLecturer();

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer profile not found.');
            return redirect()->back();
        }

        $activities = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })
        ->with(['student.user', 'lecturerComments' => function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        }, 'supervisorComments'])
        ->orderBy('date', 'desc')
        ->get();

        return view('home.lecturers.activities.view_activities', compact('activities', 'lecturer'));
    }

    public function activity_details($id)
    {
        $lecturer = $this->getCurrentLecturer();

        $activity = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })
        ->with([
            'student.user',
            'lecturerComments.lecturer.user',
            'supervisorComments.supervisor.user'
        ])
        ->find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found or not assigned to you.');
            return redirect()->back();
        }

        return view('home.lecturers.activities.activity_details', compact('activity', 'lecturer'));
    }

    public function approve_activity($id)
    {
        $lecturer = $this->getCurrentLecturer();

        $activity = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })->find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $activity->status = 'approved';
        $activity->save();

        toastr()->closeButton()->success('Activity approved successfully.');
        return redirect()->back();
    }

    public function reject_activity($id)
    {
        $lecturer = $this->getCurrentLecturer();

        $activity = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })->find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $activity->status = 'rejected';
        $activity->save();

        toastr()->closeButton()->success('Activity rejected.');
        return redirect()->back();
    }

    // ========== COMMENTING ==========

    public function add_comment(Request $request, $activity_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $activity = Activity::whereHas('student', function($query) use ($lecturer) {
            $query->where('lecturer_id', $lecturer->id);
        })->find($activity_id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = new LecturerComment();
        $comment->activity_id = $activity_id;
        $comment->lecturer_id = $lecturer->id;
        $comment->student_id = $activity->student_id;
        $comment->comment = $request->comment;
        $comment->save();

        toastr()->closeButton()->success('Comment added successfully.');
        return redirect()->back();
    }

    public function delete_comment($id)
    {
        $lecturer = $this->getCurrentLecturer();

        $comment = LecturerComment::where('id', $id)
            ->where('lecturer_id', $lecturer->id)
            ->first();

        if (!$comment) {
            toastr()->closeButton()->error('Comment not found.');
            return redirect()->back();
        }

        $comment->delete();
        toastr()->closeButton()->success('Comment deleted successfully.');
        return redirect()->back();
    }

    // ========== REPORT MANAGEMENT ==========

    public function view_reports()
    {
        $lecturer = $this->getCurrentLecturer();

        $students = Student::where('lecturer_id', $lecturer->id)
            ->whereNotNull('report')
            ->with(['user', 'company'])
            ->get();

        return view('home.lecturers.reports.view_reports', compact('students', 'lecturer'));
    }

    public function view_report($student_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $student_id)
            ->where('lecturer_id', $lecturer->id)
            ->with(['user', 'company', 'department', 'course'])
            ->first();

        if (!$student || !$student->report) {
            toastr()->closeButton()->error('Report not found.');
            return redirect()->back();
        }

        return view('home.lecturers.reports.view_report', compact('student', 'lecturer'));
    }

    public function download_report($student_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $student_id)
            ->where('lecturer_id', $lecturer->id)
            ->first();

        if (!$student || !$student->report) {
            toastr()->closeButton()->error('Report not found.');
            return redirect()->back();
        }

        $filePath = storage_path('app/public/' . $student->report);

        if (!file_exists($filePath)) {
            toastr()->closeButton()->error('Report file not found.');
            return redirect()->back();
        }

        return response()->download($filePath);
    }
    // ========== GRADING ==========

    public function grade_students()
    {
        $lecturer = $this->getCurrentLecturer();

        $students = Student::where('lecturer_id', $lecturer->id)
            ->whereNotNull('report')
            ->with(['user', 'company', 'activities'])
            ->get();

        return view('home.lecturers.grading.grade_students', compact('students', 'lecturer'));
    }

    public function submit_grade(Request $request, $student_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $student_id)
            ->where('lecturer_id', $lecturer->id)
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found.');
            return redirect()->back();
        }

        $request->validate([
            'final_grade' => 'required|string|in:A,A-,B+,B,B-,C+,C,C-,D+,D,D-,E,F',
            'grading_comments' => 'nullable|string',
        ]);

        $student->final_grade = $request->final_grade;
        $student->grading_comments = $request->grading_comments;
        $student->graded_at = now();
        $student->save();

        toastr()->closeButton()->success('Grade submitted successfully.');
        return redirect()->back();
    }

    public function edit_grade($student_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $student_id)
            ->where('lecturer_id', $lecturer->id)
            ->with(['user', 'company', 'activities'])
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found.');
            return redirect()->back();
        }

        return view('home.lecturers.grading.edit_grade', compact('student', 'lecturer'));
    }

    public function update_grade(Request $request, $student_id)
    {
        $lecturer = $this->getCurrentLecturer();

        $student = Student::where('id', $student_id)
            ->where('lecturer_id', $lecturer->id)
            ->first();

        if (!$student) {
            toastr()->closeButton()->error('Student not found.');
            return redirect()->back();
        }

        $request->validate([
            'final_grade' => 'required|string|in:A,A-,B+,B,B-,C+,C,C-,D+,D,D-,E,F',
            'grading_comments' => 'nullable|string',
        ]);

        $student->final_grade = $request->final_grade;
        $student->grading_comments = $request->grading_comments;
        $student->graded_at = now();
        $student->save();

        toastr()->closeButton()->success('Grade updated successfully.');
        return redirect('/lecturer/grade_students');
    }
}
