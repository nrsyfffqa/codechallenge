<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Protect routes
    }

    /**
     * Display the upload form and student records.
     */
    public function index(Request $request)
    {
        $students = Student::query();

        // Apply search if provided
        if ($request->has('search')) {
            $students->where('name', 'LIKE', "%{$request->search}%")
                     ->orWhere('class', 'LIKE', "%{$request->search}%");
        }

        // Apply class filter if provided
        if ($request->has('filter_class') && $request->filter_class != '') {
            $students->where('class', $request->filter_class);
        }

        $students = $students->get();
        $classes = Student::select('class')->distinct()->get();

        return view('students.upload', compact('students', 'classes'));
    }

    /**
     * Handle file upload and process student data.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();

        $newEntries = 0;
        $skippedEntries = 0;

        foreach ($worksheet->getRowIterator(2) as $row) { // Skip headers
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cells as $cell) {
                $data[] = $cell->getValue();
            }

            if (count($data) >= 4) {
                $name = trim($data[0]);
                $class = trim($data[1]);
                $level = trim($data[2]);
                $parentContact = trim($data[3]);

                // Check if the student already exists
                $exists = Student::where([
                    ['name', '=', $name],
                    ['class', '=', $class],
                    ['level', '=', $level],
                    ['parent_contact', '=', $parentContact]
                ])->exists();

                if (!$exists) {
                    Student::create([
                        'name' => $name,
                        'class' => $class,
                        'level' => $level,
                        'parent_contact' => $parentContact,
                    ]);
                    $newEntries++;
                } else {
                    $skippedEntries++;
                }
            }
        }

        return back()->with('success', "Upload complete!  ");
    }
}
