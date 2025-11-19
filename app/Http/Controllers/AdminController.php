<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Company;
use App\Models\CompanySupervisor;
use App\Models\Department;
use App\Models\Course;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ========== USER MANAGEMENT ==========

    public function index()
    {
        return view('admin.dashboard');
    }

    public function add_user()
    {
        return view('admin.users.add_user');
    }

    public function view_users()
    {
        $users = User::all();
        return view('admin.users.view_users', compact('users'));
    }

    public function upload_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'registration_number' => 'required|string|max:50|unique:users,registration_number',
            'password' => 'required|min:6',
            'usertype' => 'required|in:user,admin',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->usertype = $request->usertype;
        $user->registration_number = $request->registration_number;
        $user->password = Hash::make($request->password);
        $user->save();

        $loginUrl = url('/login');
        Mail::raw("Hello {$user->name},\n\nYour account has been created successfully.\nYou can access the system here: {$loginUrl}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to the System');
        });

        toastr()->closeButton()->success('User added successfully and email sent.');
        return redirect()->back();
    }

    public function delete_user($id)
    {
        $data = User::find($id);

        if (!$data) {
            toastr()->closeButton()->error('User not found.');
            return redirect()->back();
        }

        $data->delete();
        toastr()->closeButton()->success('User deleted successfully.');
        return redirect()->back();
    }

    public function edit_user($id)
    {
        $data = User::find($id);

        if (!$data) {
            toastr()->closeButton()->error('User not found.');
            return redirect()->back();
        }

        return view('admin.users.edit_user', compact('data'));
    }

    public function update_user(Request $request, $id)
    {
        $data = User::find($id);

        if (!$data) {
            toastr()->closeButton()->error('User not found.');
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'registration_number' => "required|string|max:50|unique:users,registration_number,{$id}",
            'usertype' => 'required|in:user,admin',
            'password' => 'nullable|min:6',
        ]);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->usertype = $request->usertype;
        $data->registration_number = $request->registration_number;

        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);
        }

        $data->save();
        toastr()->closeButton()->success('User updated successfully.');
        return redirect('/view_user');
    }

    // ========== STUDENT MANAGEMENT ==========

    public function add_student()
    {
        $users = User::where('usertype', 'user')->get();
        $departments = Department::all();
        $courses = Course::all();
        $companies = Company::all();
        $lecturers = Lecturer::all();
        $supervisors = CompanySupervisor::all();

        return view('admin.students.add_student', compact('users', 'departments', 'courses', 'companies', 'lecturers', 'supervisors'));
    }

    public function view_students()
    {
        $students = Student::with([
            'user',
            'department',
            'course',
            'company',
            'lecturer.user',
            'companySupervisor.user'
        ])->get();

        return view('admin.students.view_students', compact('students'));
    }
    public function upload_student(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:students,user_id',
            'year_of_study' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'course_id' => 'nullable|exists:courses,id',
            'company_id' => 'nullable|exists:companies,id',
            'company_supervisor_id' => 'nullable|exists:company_supervisors,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
        ]);

        $student = new Student();
        $student->user_id = $request->user_id;
        $student->year_of_study = $request->year_of_study;
        $student->department_id = $request->department_id;
        $student->course_id = $request->course_id;
        $student->company_id = $request->company_id;
        $student->company_supervisor_id = $request->company_supervisor_id;
        $student->lecturer_id = $request->lecturer_id;

        $student->save();

        $user = User::find($request->user_id);
        if ($user) {
            $user->usertype = 'student';
            $user->save();
        }

        toastr()->closeButton()->success('Student record added successfully and user type updated.');
        return redirect()->back();
    }

    public function edit_student($id)
    {
        $student = Student::with(['user', 'department', 'course', 'company', 'lecturer', 'companySupervisor'])->find($id);
        $users = User::where('usertype', 'user')->orWhere('usertype', 'student')->get();
        $departments = Department::all();
        $courses = Course::all();
        $companies = Company::all();
        $lecturers = Lecturer::all();
        $supervisors = CompanySupervisor::all();

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        return view('admin.students.edit_student', compact('student', 'users', 'departments', 'courses', 'companies', 'lecturers', 'supervisors'));
    }

    public function update_student(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        $request->validate([
            'user_id' => "required|exists:users,id|unique:students,user_id,{$id}",
            'year_of_study' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'course_id' => 'nullable|exists:courses,id',
            'company_id' => 'nullable|exists:companies,id',
            'company_supervisor_id' => 'nullable|exists:company_supervisors,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
        ]);

        if ($student->user_id != $request->user_id) {
            $oldUser = User::find($student->user_id);
            if ($oldUser) {
                $oldUser->usertype = 'user';
                $oldUser->save();
            }

            // Update new user's type to 'student'
            $newUser = User::find($request->user_id);
            if ($newUser) {
                $newUser->usertype = 'student';
                $newUser->save();
            }
        }

        $student->user_id = $request->user_id;
        $student->year_of_study = $request->year_of_study;
        $student->department_id = $request->department_id;
        $student->course_id = $request->course_id;
        $student->company_id = $request->company_id;
        $student->company_supervisor_id = $request->company_supervisor_id;
        $student->lecturer_id = $request->lecturer_id;

        $student->save();

        toastr()->closeButton()->success('Student record updated successfully.');
        return redirect('/admin/view_students');
    }

    public function delete_student($id)
    {
        $student = Student::find($id);

        if (!$student) {
            toastr()->closeButton()->error('Student record not found.');
            return redirect()->back();
        }

        // Revert user's usertype back to 'user'
        $user = User::find($student->user_id);
        if ($user) {
            $user->usertype = 'user';
            $user->save();
        }

        if ($student->report) {
            Storage::disk('public')->delete($student->report);
        }

        $student->delete();
        toastr()->closeButton()->success('Student record deleted successfully and user type reverted.');
        return redirect()->back();
    }

    // ========== LECTURER MANAGEMENT ==========

    public function add_lecturer()
    {
        $departments = Department::all();
        $users = User::where('usertype', 'user')->get();
        return view('admin.lecturers.add_lecturer', compact('departments', 'users'));
    }

    public function view_lecturers()
    {
        $lecturers = Lecturer::with(['department', 'user'])->get();
        return view('admin.lecturers.view_lecturers', compact('lecturers'));
    }

    public function upload_lecturer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:lecturers,user_id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
        ]);

        $lecturer = new Lecturer();
        $lecturer->user_id = $request->user_id;
        $lecturer->department_id = $request->department_id;
        $lecturer->specialization = $request->specialization;
        // $lecturer->user_id = $request->user_id;
        $lecturer->save();

        // Update user's usertype to 'lecturer' if user_id is provided
        if ($request->user_id) {
            $user = User::find($request->user_id);
            if ($user) {
                $user->usertype = 'lecturer';
                $user->save();
            }
        }

        toastr()->closeButton()->success('Lecturer added successfully.');
        return redirect('/admin/view_lecturers');
    }

    public function edit_lecturer($id)
    {
        $lecturer = Lecturer::find($id);
        $departments = Department::all();
        $users = User::where('usertype', 'user')->orWhere('usertype', 'lecturer')->get();

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer not found.');
            return redirect()->back();
        }

        return view('admin.lecturers.edit_lecturer', compact('lecturer', 'departments', 'users'));
    }

    public function update_lecturer(Request $request, $id)
    {
        $lecturer = Lecturer::find($id);

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer not found.');
            return redirect()->back();
        }

        $request->validate([
            'user_id' => "required|exists:users,id|unique:lecturers,user_id,{$id}",
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
        ]);

        // Handle user_id changes
        if ($lecturer->user_id != $request->user_id) {
            if ($lecturer->user_id) {
                $oldUser = User::find($lecturer->user_id);
                if ($oldUser) {
                    $oldUser->usertype = 'user';
                    $oldUser->save();
                }
            }

            $newUser = User::find($request->user_id);
            if ($newUser) {
                $newUser->usertype = 'lecturer';
                $newUser->save();
            }
        }

        $lecturer->user_id = $request->user_id;
        $lecturer->department_id = $request->department_id;
        $lecturer->specialization = $request->specialization;
        $lecturer->save();

        toastr()->closeButton()->success('Lecturer updated successfully.');
        return redirect('/admin/view_lecturers');
    }
    public function delete_lecturer($id)
    {
        $lecturer = Lecturer::find($id);

        if (!$lecturer) {
            toastr()->closeButton()->error('Lecturer not found.');
            return redirect()->back();
        }

        // Revert user's usertype back to 'user'
        if ($lecturer->user_id) {
            $user = User::find($lecturer->user_id);
            if ($user) {
                $user->usertype = 'user';
                $user->save();
            }
        }

        $lecturer->delete();
        toastr()->closeButton()->success('Lecturer deleted successfully.');
        return redirect()->back();
    }

    // ========== COMPANY MANAGEMENT ==========

    public function add_company()
    {
        return view('admin.companies.add_company');
    }

    public function view_companies()
    {
        $companies = Company::all();
        return view('admin.companies.view_companies', compact('companies'));
    }

    public function upload_company(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
        ]);

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->industry = $request->industry;
        $company->save();

        toastr()->closeButton()->success('Company added successfully.');
        return redirect('admin/view_companies');
    }

    public function edit_company($id)
    {
        $company = Company::find($id);

        if (!$company) {
            toastr()->closeButton()->error('Company not found.');
            return redirect()->back();
        }

        return view('admin.companies.edit_company', compact('company'));
    }

    public function update_company(Request $request, $id)
    {
        $company = Company::find($id);

        if (!$company) {
            toastr()->closeButton()->error('Company not found.');
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
        ]);

        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->industry = $request->industry;
        $company->save();

        toastr()->closeButton()->success('Company updated successfully.');
        return redirect('admin/view_companies');
    }

    public function delete_company($id)
    {
        $company = Company::find($id);

        if (!$company) {
            toastr()->closeButton()->error('Company not found.');
            return redirect()->back();
        }

        $company->delete();
        toastr()->closeButton()->success('Company deleted successfully.');
        return redirect()->back();
    }

    // ========== COMPANY SUPERVISOR MANAGEMENT ==========

    public function add_supervisor()
    {
        $companies = Company::all();
        $users = User::where('usertype', 'user')->get();
        return view('admin.supervisors.add_supervisor', compact('companies', 'users'));
    }

    public function view_supervisors()
    {
        $supervisors = CompanySupervisor::with(['company', 'user'])->get();
        return view('admin.supervisors.view_supervisors', compact('supervisors'));
    }

    public function upload_supervisor(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:company_supervisors,user_id',
            'company_id' => 'required|exists:companies,id',
            'position' => 'nullable|string|max:255',
        ]);

        $supervisor = new CompanySupervisor();
        $supervisor->user_id = $request->user_id;
        $supervisor->company_id = $request->company_id;
        $supervisor->position = $request->position;
        $supervisor->save();

        // Update user's usertype to 'supervisor'
        $user = User::find($request->user_id);
        if ($user) {
            $user->usertype = 'supervisor';
            $user->save();
        }

        toastr()->closeButton()->success('Company supervisor added successfully.');
        return redirect('admin/view_supervisors');
    }

    public function edit_supervisor($id)
    {
        $supervisor = CompanySupervisor::find($id);
        $companies = Company::all();
        $users = User::where('usertype', 'user')->orWhere('usertype', 'supervisor')->get();

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor not found.');
            return redirect()->back();
        }

        return view('admin.supervisors.edit_supervisor', compact('supervisor', 'companies', 'users'));
    }

    public function update_supervisor(Request $request, $id)
    {
        $supervisor = CompanySupervisor::find($id);

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor not found.');
            return redirect('admin/view_supervisors');
        }

        $request->validate([
            'user_id' => "required|exists:users,id|unique:company_supervisors,user_id,{$id}",
            'company_id' => 'required|exists:companies,id',
            'position' => 'nullable|string|max:255',
        ]);

        // Handle user_id changes
        if ($supervisor->user_id != $request->user_id) {
            if ($supervisor->user_id) {
                $oldUser = User::find($supervisor->user_id);
                if ($oldUser) {
                    $oldUser->usertype = 'user';
                    $oldUser->save();
                }
            }

            $newUser = User::find($request->user_id);
            if ($newUser) {
                $newUser->usertype = 'supervisor';
                $newUser->save();
            }
        }

        $supervisor->user_id = $request->user_id;
        $supervisor->company_id = $request->company_id;
        $supervisor->position = $request->position;
        $supervisor->save();

        toastr()->closeButton()->success('Supervisor updated successfully.');
        return redirect('admin/view_supervisors');
    }

    public function delete_supervisor($id)
    {
        $supervisor = CompanySupervisor::find($id);

        if (!$supervisor) {
            toastr()->closeButton()->error('Supervisor not found.');
            return redirect()->back();
        }

        // Revert user's usertype back to 'user'
        if ($supervisor->user_id) {
            $user = User::find($supervisor->user_id);
            if ($user) {
                $user->usertype = 'user';
                $user->save();
            }
        }

        $supervisor->delete();
        toastr()->closeButton()->success('Supervisor deleted successfully.');
        return redirect()->back();
    }

    // ========== DEPARTMENT MANAGEMENT ==========

    public function add_department()
    {
        return view('admin.departments.add_department');
    }

    public function view_departments()
    {
        $departments = Department::all();
        return view('admin.departments.view_departments', compact('departments'));
    }

    public function upload_department(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        $department = new Department();
        $department->name = $request->name;
        $department->code = $request->code;
        $department->description = $request->description;
        $department->save();

        toastr()->closeButton()->success('Department added successfully.');
        return redirect()->back();
    }

    public function edit_department($id)
    {
        $department = Department::find($id);

        if (!$department) {
            toastr()->closeButton()->error('Department not found.');
            return redirect()->back();
        }

        return view('admin.departments.edit_department', compact('department'));
    }

    public function update_department(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            toastr()->closeButton()->error('Department not found.');
            return redirect()->back();
        }

        $request->validate([
            'name' => "required|string|max:255|unique:departments,name,{$id}",
            'code' => "nullable|string|max:50|unique:departments,code,{$id}",
            'description' => 'nullable|string',
        ]);

        $department->name = $request->name;
        $department->code = $request->code;
        $department->description = $request->description;
        $department->save();

        toastr()->closeButton()->success('Department updated successfully.');
        return redirect('admin/view_departments');
    }

    public function delete_department($id)
    {
        $department = Department::find($id);

        if (!$department) {
            toastr()->closeButton()->error('Department not found.');
            return redirect()->back();
        }

        $department->delete();
        toastr()->closeButton()->success('Department deleted successfully.');
        return redirect()->back();
    }

    // ========== COURSE MANAGEMENT ==========

    public function add_course()
    {
        $departments = Department::all();
        return view('admin.courses.add_course', compact('departments'));
    }

    public function view_courses()
    {
        $courses = Course::with('department')->get();
        return view('admin.courses.view_courses', compact('courses'));
    }

    public function upload_course(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:courses,code',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->code = $request->code;
        $course->department_id = $request->department_id;
        $course->description = $request->description;
        $course->save();

        toastr()->closeButton()->success('Course added successfully.');
        return redirect()->back();
    }

    public function edit_course($id)
    {
        $course = Course::find($id);
        $departments = Department::all();

        if (!$course) {
            toastr()->closeButton()->error('Course not found.');
            return redirect()->back();
        }

        return view('admin.courses.edit_course', compact('course', 'departments'));
    }

    public function update_course(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            toastr()->closeButton()->error('Course not found.');
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => "nullable|string|max:50|unique:courses,code,{$id}",
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $course->name = $request->name;
        $course->code = $request->code;
        $course->department_id = $request->department_id;
        $course->description = $request->description;
        $course->save();

        toastr()->closeButton()->success('Course updated successfully.');
        return redirect('/admin/view_courses');
    }

    public function delete_course($id)
    {
        $course = Course::find($id);

        if (!$course) {
            toastr()->closeButton()->error('Course not found.');
            return redirect()->back();
        }

        $course->delete();
        toastr()->closeButton()->success('Course deleted successfully.');
        return redirect()->back();
    }

    // ========== ACTIVITY MANAGEMENT ==========

    public function add_activity()
    {
        $students = Student::with('user')->get();
        return view('admin.activities.add_activity', compact('students'));
    }

    public function view_activities()
    {
        $activities = Activity::with('student.user')->get();
        return view('admin.activities.view_activities', compact('activities'));
    }

    public function upload_activity(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'description' => 'required|string',
            'hours' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $activity = new Activity();
        $activity->student_id = $request->student_id;
        $activity->date = $request->date;
        $activity->description = $request->description;
        $activity->hours = $request->hours;
        $activity->status = $request->status ?? 'pending';
        $activity->save();

        toastr()->closeButton()->success('Activity added successfully.');
        return redirect()->back();
    }

    public function edit_activity($id)
    {
        $activity = Activity::find($id);
        $students = Student::with('user')->get();

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        return view('admin.activities.edit_activity', compact('activity', 'students'));
    }

    public function update_activity(Request $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'description' => 'required|string',
            'hours' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $activity->student_id = $request->student_id;
        $activity->date = $request->date;
        $activity->description = $request->description;
        $activity->hours = $request->hours;
        $activity->status = $request->status ?? 'pending';
        $activity->save();

        toastr()->closeButton()->success('Activity updated successfully.');
        return redirect('/admin/view_activities');
    }

    public function delete_activity($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            toastr()->closeButton()->error('Activity not found.');
            return redirect()->back();
        }

        $activity->delete();
        toastr()->closeButton()->success('Activity deleted successfully.');
        return redirect()->back();
    }
}
