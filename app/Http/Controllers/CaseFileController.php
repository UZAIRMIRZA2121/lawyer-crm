<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\CaseFile;
use Illuminate\Http\Request;

class CaseFileController extends Controller
{
    public function index(CaseModel $case)
    {
        $files = $case->files()->latest()->get();
        return view('case-files.index', compact('case', 'files'));
    }

    public function create(CaseModel $case)
    {
        $all_case_files = CaseFile::where('case_id', $case->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('case-files.create', compact('case', 'all_case_files'));
    }
    public function store(Request $request, CaseModel $case)
    {


        // Initialize the sequence properly
        $sequence = $case->files()->max('sequence') ?? 0;

        try {
            foreach ($request->file('files') as $file) {
                $sequence++;

                $fileName = time() . '_' . $file->getClientOriginalName();

                $filePath = $file->storeAs(
                    $case->id,
                    $fileName,
                    'public'
                );

                if (!$filePath) {
                    dd('File storage failed for: ' . $fileName);
                }

                CaseFile::create([
                    'case_id' => $case->id,
                    'user_id' => auth()->id() ?? 1,
                    'file_path' => $filePath,
                    'sequence' => $sequence,
                ]);
            }
        } catch (\Exception $e) {
            // Dump the exception details for debugging
            dd('Error:', $e->getMessage(), 'File:', $e->getFile(), 'Line:', $e->getLine());
        }

        return redirect()->back()->with('success', 'Files uploaded successfully.');
    }



    public function destroy(CaseFile $file)
    {
        // Delete file from storage
        \Storage::disk('public')->delete($file->file_path);

        // Delete record
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

}
