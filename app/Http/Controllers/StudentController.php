<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;


class StudentController extends Controller
{
    public function index(){
        $groupStudents = Student::with( 'groups')->get();
        $students = Student::all();

        return Inertia::render('Students/Student', [
            'students' => $students,
            'groupStudents' => $groupStudents,
        ]);
    }



    public function show($id){
        $student = Student::findOrFail($id);
        return Inertia::render('Students/StudentShow', [
            'student' => $student
        ]);
    }

    public function create(){
        $groups = Group::all();
        return Inertia::render('Students/StudentCreate', [
            'groups' => $groups
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'birth_date' => 'required|date',
            'balance' => 'required|numeric|min:0',
            'group_id' => 'required|exists:groups,id',
        ]);

        // 1. Studentni yaratamiz
        $student = Student::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'balance' => $data['balance'],
        ]);

        // 2. group_students jadvaliga yozamiz
        GroupStudent::create([
            'student_id' => $student->id,
            'group_id' => $data['group_id'],
        ]);

        return redirect()->route('students.index')->with('success', 'Talaba yaratildi va guruhga biriktirildi.');
    }


    public function edit($id)
    {
        $student = Student::findOrFail($id);

        return Inertia::render('Students/StudentUpdate', [
            'student' => $student
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'birth_date' => 'required|date',
            'balance' => 'required|numeric|min:0',
        ]);

        $student = Student::findOrFail($id);
        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
