<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student; // Make sure Student is imported if not already
use App\Models\Teacher; // Make sure Teacher is imported if not already
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        $groups = Group::with('teacher')->get();

        return Inertia::render('Groups/Groups', [
            'groups' => $groups,
            'teachers' => $teachers
        ]);
    }


    public function show($id)
    {
        // Eager load both 'students' and 'teacher' relationships
        $group = Group::with(['students', 'teacher'])->findOrFail($id);
        return Inertia::render('Groups/GroupShow', [
            'group' => $group
        ]);
    }
    public function create()
    {
        $teachers=Teacher::all();
        return Inertia::render('Groups/GroupCreate', [
            'teachers' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'monthly_fee' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'date' => 'required|date',
        ]);

        $group = Group::create($data);

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $teachers = Teacher::all(['id', 'name']); // Faqat kerakli maydonlarni olish tavsiya etiladi

        return Inertia::render('Groups/GroupUpdate', [
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'teacher_id' => $group->teacher_id,
                'monthly_fee' => $group->monthly_fee,
                // Sanalar modeldagi accessorlar orqali avtomatik formatlanadi
                'start_date' => $group->start_date, // Endi bu YYYY-MM-DD formatida bo'ladi
                'date' => $group->date,             // Endi bu YYYY-MM-DD formatida bo'ladi
            ],
            'teachers' => $teachers
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'monthly_fee' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'date' => 'required|date',
        ]);

        $group = Group::findOrFail($id);
        $group->update($data);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
}
