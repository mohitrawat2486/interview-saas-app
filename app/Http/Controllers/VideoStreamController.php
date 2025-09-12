<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoStreamController extends Controller
{
    public function stream(Answer $answer){
        $user = Auth::user();
        $submission = $answer->submission()->with('candidate')->first();
        $canView = $user && (
            $user->role === 'admin' || $user->role === 'reviewer' || $user->id === $submission->candidate_id
        );
        abort_unless($canView, 403);

        $path = Storage::disk('public')->path($answer->video_path); // switch disk to 'private' if needed
        return response()->file($path, ['Content-Type'=>mime_content_type($path)]);
    }
}
