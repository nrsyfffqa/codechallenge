<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,xls|max:5120', // 5MB max
        ];
    }

    /**
     * Validate the spreadsheet data after the file validation passes.
     *
     * @return array
     */
    public function validateSpreadsheetData(): array
    {
        $file = $this->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        $validatedData = [];
        $errors = [];
        $rowNumber = 2; // Start from row 2 (after header)

        foreach ($rows as $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                $rowNumber++;
                continue;
            }

            // Validate each row
            $rowData = [
                'name' => trim($row[0] ?? ''),
                'class' => trim($row[1] ?? ''),
                'level' => trim($row[2] ?? ''),
                'parent_contact' => trim($row[3] ?? ''),
            ];

            // Name validation
            if (empty($rowData['name'])) {
                $errors[] = "Row {$rowNumber}: Name is required";
            } elseif (strlen($rowData['name']) > 255) {
                $errors[] = "Row {$rowNumber}: Name must not exceed 255 characters";
            }

            // Class validation
            if (empty($rowData['class'])) {
                $errors[] = "Row {$rowNumber}: Class is required";
            } elseif (strlen($rowData['class']) > 50) {
                $errors[] = "Row {$rowNumber}: Class must not exceed 50 characters";
            }

            // Level validation
            if (empty($rowData['level'])) {
                $errors[] = "Row {$rowNumber}: Level is required";
            } elseif (!is_numeric($rowData['level']) || $rowData['level'] < 1) {
                $errors[] = "Row {$rowNumber}: Level must be a positive number";
            }

            // Parent contact validation
            if (empty($rowData['parent_contact'])) {
                $errors[] = "Row {$rowNumber}: Parent contact is required";
            } else {
                // Remove any non-numeric characters
                $cleanNumber = preg_replace('/[^0-9]/', '', $rowData['parent_contact']);
                
                // Check if number is between 10 and 11 digits
                if (strlen($cleanNumber) < 10 || strlen($cleanNumber) > 11) {
                    $errors[] = "Row {$rowNumber}: Parent contact must be 10 or 11 digits";
                }
            }

            // If row is valid, add to validated data
            if (empty($errors)) {
                // Ensure parent_contact is properly formatted
                $cleanNumber = preg_replace('/[^0-9]/', '', $rowData['parent_contact']);
                $rowData['parent_contact'] = $cleanNumber;
                
                $validatedData[] = $rowData;
            }

            $rowNumber++;
        }

        if (!empty($errors)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []), 
                response()->json(['errors' => $errors], 422)
            );
        }

        return $validatedData;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select an Excel file to upload.',
            'file.mimes' => 'The file must be an Excel file (xlsx or xls).',
            'file.max' => 'The file size must not exceed 5MB.',
        ];
    }
}
