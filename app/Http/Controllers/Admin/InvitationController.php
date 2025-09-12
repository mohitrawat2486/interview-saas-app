<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index(Interview $interview){
        $invitations = $interview->invitations()->latest()->paginate(50);
        return view('admin.invitations.index', compact('interview','invitations'));
    }

    public function create(Interview $interview){
        return view('admin.invitations.create', compact('interview'));
    }

    public function store(Request $r, Interview $interview){
        $data = $r->validate([
            'candidate_email'=>'required|email',
            'expires_at'=>'nullable|date'
        ]);
        $inv = $interview->invitations()->create([
            'candidate_email'=>$data['candidate_email'],
            'token'=>Str::uuid()->toString(),
            'expires_at'=>$data['expires_at'] ?? null,
            'status'=>'invited',
        ]);
        return redirect()->route('admin.interviews.invitations.index', $interview)
            ->with('ok','Invitation created: '.route('candidate.start',$inv->token));
    }

    public function destroy(Interview $interview, Invitation $invitation){
        $invitation->delete();
        return redirect()->route('admin.interviews.invitations.index', $interview)->with('ok','Deleted');
    }
}
