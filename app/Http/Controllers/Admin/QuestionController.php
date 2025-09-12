<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Interview $interview){
        $questions = $interview->questions()->get();
        return view('admin.questions.index', compact('interview','questions'));
    }

    public function create(Interview $interview){
        return view('admin.questions.create', compact('interview'));
    }

    public function store(Request $r, Interview $interview){
        $data = $r->validate([
            'prompt'=>'required|string',
            'order'=>'required|integer|min:1',
            'time_limit_seconds'=>'nullable|integer|min:10|max:900',
            'thinking_time_seconds'=>'nullable|integer|min:0|max:60',
            'allow_retake'=>'nullable|boolean',
        ]);
        $interview->questions()->create([
            ...$data,
            'time_limit_seconds'=>$data['time_limit_seconds'] ?? ($interview->settings['time_limit_per_question'] ?? 120),
            'allow_retake'=>$data['allow_retake'] ?? ($interview->settings['allow_retakes'] ?? true),
        ]);
        return redirect()->route('admin.interviews.questions.index', $interview)->with('ok','Question added');
    }

    public function edit(Question $question){
        $interview = $question->interview;
        return view('admin.questions.edit', compact('interview','question'));
    }

    public function update(Request $r, Question $question){
        $data = $r->validate([
            'prompt'=>'required|string',
            'order'=>'required|integer|min:1',
            'time_limit_seconds'=>'nullable|integer|min:10|max:900',
            'thinking_time_seconds'=>'nullable|integer|min:0|max:60',
            'allow_retake'=>'nullable|boolean',
        ]);
        $question->update($data);
        return redirect()->route('admin.interviews.questions.index', $question->interview_id)->with('ok','Updated');
    }

    public function destroy(Question $question){
        $iid = $question->interview_id;
        $question->delete();
        return redirect()->route('admin.interviews.questions.index', $iid)->with('ok','Deleted');
    }
}
