<?php

namespace App\Http\Controllers;

use App\File;
use App\Requester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;

class SignatureController extends Controller
{

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


    public function update(Request $request, Requester $requester)
    {
        $requester = Requester::where('id', $requester->id)->first();

        DB::transaction(function () use ($requester) {
            $requester->update([
                'updated' => now()
            ]);

            Requester::create([
                'requester_id' => $requester['id'],
                'status' => 'signed'
            ]);
        });

        redirect()->route('signature.index')->with(['success' => 'Berhasil Approve Document']);
    }
}
