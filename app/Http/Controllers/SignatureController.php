<?php

namespace App\Http\Controllers;

use App\File;
use App\Requester;
use App\RequesterStatus;
use App\Traits\SignatureTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;

class SignatureController extends Controller
{
    use SignatureTrait;

    public function index()
    {
        $signRequests = Requester::with(['status', 'owner', 'recipient'])
            ->where('recipient_id', auth()->user()->id)->get();

        return view('dashboard.signature.index', compact('signRequests'));
    }

    public function setSign(File $file)
    {
        $file->load('users.request');
        return view('dashboard.signature.show', compact('file'));
    }


    public function update(Request $request, $id)
    {
        $requester = Requester::where('id', $id)->first();

        DB::transaction(function () use ($requester) {
            $requester->update([
                'updated' => now()
            ]);

            RequesterStatus::create([
                'requester_id' => $requester['id'],
                'name' => 'signed'
            ]);

            $file = File::where('id', $requester['file_id'])->first();

            $this->digitalSignatureApprove($file, auth()->user());
        });

        return redirect()->route('signature.index')->with(['success' => 'Berhasil Approve Document']);
    }
}
