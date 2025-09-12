<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Review;
use App\Models\ReviewItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(){
        $subs = Submission::with(['candidate','interview'])->where('status','submitted')->latest()->paginate(20);
        return view('review.index', compact('subs'));
    }

    public function show(Submission $submission){
        $submission->load(['answers.question','candidate','interview.questions']);
        return view('review.show', compact('submission'));
    }

    public function store(Request $r, Submission $submission){
        $data = $r->validate([
            'overall_score'=>'nullable|integer|min:1|max:10',
            'overall_comment'=>'nullable|string',
            'items'=>'nullable|array',
            'items.*.score'=>'nullable|integer|min:1|max:10',
            'items.*.comment'=>'nullable|string',
        ]);

        $review = Review::updateOrCreate(
            ['submission_id'=>$submission->id,'reviewer_id'=>Auth::id()],
            ['overall_score'=>$data['overall_score'] ?? null, 'overall_comment'=>$data['overall_comment'] ?? null]
        );

        foreach (($data['items'] ?? []) as $qid => $vals) {
            ReviewItem::updateOrCreate(
                ['review_id'=>$review->id,'question_id'=>$qid],
                ['score'=>$vals['score'] ?? null, 'comment'=>$vals['comment'] ?? null]
            );
        }

        return back()->with('ok','Review saved');
    }
}
