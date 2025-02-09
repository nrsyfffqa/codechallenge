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
        $errors = [];

        foreach ($worksheet->getRowIterator(2) as $rowIndex => $row) { // Skip headers
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cells as $cell) {
                $data[] = $cell->getValue();
            }

            $name = trim($data[0] ?? '');
            $class = trim($data[1] ?? '');
            $level = trim($data[2] ?? '');
            $parentContact = trim($data[3] ?? '');

            // Validation rules
            if (empty($name) || empty($class) || empty($level) || empty($parentContact)) {
                $errors[] = "Row $rowIndex: All fields must be filled.";
                continue;
            }

            if (strlen($name) > 100) {
                $errors[] = "Row $rowIndex: Name must not exceed 100 characters.";
                continue;
            }
            
            if (!is_string($class)) {
                $errors[] = "Row $rowIndex: Class must be a valid string.";
                continue;
            }
            
            if (!is_numeric($level) || $level < 1 || $level > 6) {
                $errors[] = "Row $rowIndex: Level must be an integer between 1 and 6.";
                continue;
            }
            
            if (!preg_match('/^\d{10,11}$/', $parentContact)) {
                $errors[] = "Row $rowIndex: Parent contact must be 10 or 11 digits.";
                continue;
            }

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

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        return back()->with('success', "Upload complete! $newEntries new records added, $skippedEntries skipped.");
    }
}
