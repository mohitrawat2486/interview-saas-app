<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Submission;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function start(string $token){
        $inv = Invitation::where('token',$token)->firstOrFail();
        if ($inv->expires_at && now()->greaterThan($inv->expires_at)) {
            $inv->update(['status'=>'expired']);
            abort(410, 'Link expired');
        }
        $inv->load('interview.questions');
        return view('candidate.start', ['inv'=>$inv, 'interview'=>$inv->interview]);
    }

    public function begin(Request $r, string $token){
        $inv = Invitation::where('token',$token)->firstOrFail();

        $user = Auth::user();
        if (!$user) {
            $user = User::where('email', $inv->candidate_email)->first();
            if (!$user) {
                $user = new User();
                $user->name = Str::before($inv->candidate_email, '@');
                $user->email = $inv->candidate_email;
                $user->password = bcrypt(Str::random(16));
                $user->role = 'candidate'; // avoids fillable issues by direct assignment
                $user->save();
            }
            Auth::login($user);
        }

        $submission = Submission::firstOrCreate(
            ['interview_id'=>$inv->interview_id, 'candidate_id'=>$user->id],
            ['started_at'=>now(), 'status'=>'in_progress']
        );
        $inv->update(['status'=>'started','candidate_user_id'=>$user->id]);

        return redirect()->route('candidate.question', [$submission->id, 1]);
    }

    public function question(Submission $submission, int $order){
        $this->authorizeViewSubmission($submission);
        $submission->load('interview.questions');
        $question = $submission->interview->questions()->where('order',$order)->firstOrFail();
        $settings = $submission->interview->settings ?? [];
        return view('candidate.question', compact('submission','question','settings','order'));
    }

    public function upload(Request $r, Submission $submission, Question $question){
        $this->authorizeViewSubmission($submission);
        $data = $r->validate([
            'video'=>'required|file|mimetypes:video/webm,video/mp4,video/ogg|max:204800',
            'duration_seconds'=>'nullable|integer|min:1|max:3600',
            'retake_number'=>'nullable|integer|min:1|max:10',
        ]);

        $path = $r->file('video')->store("interviews/{$submission->interview_id}/submissions/{$submission->id}", 'public');

        Answer::create([
            'submission_id'=>$submission->id,
            'question_id'=>$question->id,
            'video_path'=>$path,
            'duration_seconds'=>$data['duration_seconds'] ?? null,
            'retake_number'=>$data['retake_number'] ?? 1,
            'recorded_at'=>now(),
        ]);

        return response()->json(['ok'=>true,'next'=>route('candidate.question', [$submission->id, $question->order+1])]);
    }

    public function finalize(Request $r, Submission $submission){
        $this->authorizeViewSubmission($submission);
        $submission->update(['submitted_at'=>now(),'status'=>'submitted']);
        return view('candidate.thanks');
    }

    private function authorizeViewSubmission(Submission $submission){
        $user = Auth::user();
        abort_if(!$user || $user->id !== $submission->candidate_id, 403);
    }
}
