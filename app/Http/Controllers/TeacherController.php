<?php

    namespace App\Http\Controllers;

    use App\Models\Teacher;
    use Illuminate\Http\Request;
    use Inertia\Inertia;

    class TeacherController extends Controller
    {
        public function index()
        {

            $teachers = Teacher::select('id', 'name', 'phone', 'created_at')->get();

            return Inertia::render('Teachers/Teachers', [
                'teachers' => $teachers
            ]);
        }


        public function show($id){
            $teacher = Teacher::findOrFail($id);

            return Inertia::render('Teachers/TeacherShow', [
                'teacher' => $teacher
            ]);
        }



        public function store(Request $request){;
            $data = request()->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $data['password'] = bcrypt($data['password']);

            Teacher::create($data);

            return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
        }


        public function create()
        {
            return Inertia::render('Teachers/TeacherCreate');
        }


        public function edit($id)
        {

            $teacher = Teacher::findOrFail($id);

            return Inertia::render('Teachers/TeachersUpdate', [
                'teacher' => $teacher
            ]);
        }

        public function update(Request $request, $id)
        {
            $data = request()->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if ($data['password']) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $teacher = Teacher::findOrFail($id);
            $teacher->update($data);

            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
        }

        public function destroy($id)
        {
            $model = Teacher::findOrFail($id);
            $model->delete();

            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
        }



    }
