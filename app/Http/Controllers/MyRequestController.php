<?php

namespace App\Http\Controllers;

use App\Requester;
use App\RequesterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyRequestController extends Controller
{
    public function index()
    {
        return view('dashboard.myrequest.index');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'file_id' => 'required|exists:files,id',
            'recipient_id' => 'required|exists|users,id',
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
}
