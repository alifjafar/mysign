<?php

namespace App\Http\Controllers;

use App\File;
use App\Traits\SignatureTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    use SignatureTrait;

    public function index()
    {
        if (Auth::user()->isAdmin) {
            $files = File::all();
        } else {
            $files = User::with(['files'])->whereId(Auth::user()->id)->first()->files;
        }

        return view('dashboard.files.index', compact('files'));
    }

    public function store(Request $request)
    {
        Session::flash('showModal', 'Show Modal');
        $this->validate($request, [
            'file' => 'required|file|mimes:pdf'
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = 'pdf/' . md5($user->username) . '/';
        $size = $file->getSize();
        $mime = $file->getMimeType();

        DB::transaction(function () use ($user, $filename, $path, $size, $mime, $file) {
            $uploadedFile = File::create([
                'id' => Str::orderedUuid()->getHex(),
                'filename' => $filename,
                'path' => $path,
                'size' => $size,
                'mime' => $mime
            ]);

            $uploadedFile->users()->attach($user->id);
            Storage::putFileAs($path, $file, 'original_' . $filename);
            Storage::putFileAs($path, $file, $filename);

            $this->digitalSignatureUpload($uploadedFile, $user);
        });
        Session::forget('showModal');
        return back()->with(['success' => 'Berhasil Upload File']);
    }

    public function show(File $file)
    {
        return view('dashboard.files.show', compact('file'));
    }


    public function destroy(File $file)
    {
        Storage::delete($file['path'] . $file['filename']);
        Storage::delete($file['path'] . 'original_' . $file['filename']);
        $file->delete();

        Session::flash('success', 'Berhasil Menghapus File');
        return route('files.index');
    }

    public function getDocument($id)
    {
        $document = File::findOrFail($id);

        $filePath = $document->path . $document->filename;

        // file not found
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404);
        }

        $pdfContent = Storage::disk('local')->get($filePath);

        // for pdf, it will be 'application/pdf'
        $type = Storage::disk('local')->mimeType($filePath);

        return response($pdfContent, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'inline; filename="' . $document['flename'] . '"'
        ]);
    }
}
