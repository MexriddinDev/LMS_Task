<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Student;
use App\Models\Debt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('groups')->get();
        return Inertia::render('Students/Student', [
            'students' => $students,
        ]);
    }

    public function show($id)
    {
        $student = Student::with('groups')->findOrFail($id);
        return Inertia::render('Students/StudentShow', [
            'student' => $student
        ]);
    }

    public function create()
    {
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

        // 1. O'quvchi yaratish
        $student = Student::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'balance' => $data['balance'],
        ]);

        // 2. O'quvchini guruhga biriktirish
        GroupStudent::create([
            'student_id' => $student->id,
            'group_id' => $data['group_id'],
        ]);

        // 3. Qarzdorlik yozuvini yaratish
        $group = Group::findOrFail($data['group_id']);
        Debt::create([
            'student_id' => $student->id,
            'group_id' => $data['group_id'],
            'amount' => $group->monthly_fee,
            'month' => Carbon::now()->addMonth()->startOfMonth()->toDateString(),
            'paid_amount' => 0,
            'status' => 'unpaid',
            'is_paid' => false,
        ]);

        return redirect()->route('students.index')->with('success', 'O\'quvchi yaratildi va guruhga biriktirildi.');
    }

    public function edit($id)
    {
        $student = Student::with('groups')->findOrFail($id);
        $groups = Group::all();

        return Inertia::render('Students/StudentUpdate', [
            'student' => $student,
            'groups' => $groups,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'birth_date' => 'nullable|date',
            'balance' => 'required|numeric|min:0',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $student = Student::findOrFail($id);
        $student->update($validated);

        // Guruhni yangilash
        if (isset($validated['group_id'])) {
            GroupStudent::updateOrCreate(
                ['student_id' => $student->id],
                ['group_id' => $validated['group_id']]
            );
        } else {
            GroupStudent::where('student_id', $student->id)->delete();
        }

        return redirect()->route('students.index')->with('success', 'O\'quvchi yangilandi!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'O\'quvchi o\'chirildi.');
    }
}
