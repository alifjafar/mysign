<?php

namespace App\Http\Controllers;

use App\Requester;
use App\RequesterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MyRequestController extends Controller
{
    public function index()
    {
        $requesters = Requester::with(['status', 'owner', 'recipient'])->where('user_id', Auth::user()->id)->get();

        return view('dashboard.request.index', compact('requesters'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'file_id' => 'required|exists:files,id',
            'recipient_id' => 'required|exists:users,id',
        ]);

        DB::transaction(function () use ($validated) {
            $requester = Requester::create([
                'user_id' => auth()->user()->id,
                'file_id' => $validated['file_id'],
                'recipient_id' => $validated['recipient_id'],
                'requested' => now()
            ]);

            RequesterStatus::create([
                'requester_id' => $requester['id'],
                'status' => 'pending'
            ]);
        });

        return redirect()->route('my-request.index');

    }

    public function destroy($requester)
    {
        Requester::findOrFail($requester)->delete();

        Session::flash('success','Berhasil Menghapus Request');
        return route('my-request.index');
    }
}
