<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    // app/Http/Controllers/Admin/InterviewController.php
    public function index(Request $r) {
        $builder = \App\Models\Interview::query()->withCount(['questions','submissions']);

        if ($term = trim($r->input('q', ''))) {
            $builder->where('title', 'like', "%{$term}%");
        }

        $sort = $r->input('sort', '-created_at');
        $dir  = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $col  = ltrim($sort, '-');
        in_array($col, ['created_at','title','questions_count','submissions_count'], true)
            ? $builder->orderBy($col, $dir)
            : $builder->latest();

        $perPage = max(5, min(50, (int) $r->input('per', 10)));
        $interviews = $builder->paginate($perPage)->withQueryString();

        if ($r->ajax()) {
            // return only the list/grid HTML for replacement
            return response()->view('admin.interviews._list', compact('interviews'));
        }
        return view('admin.interviews.index', compact('interviews'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
        ]);
        $interview = \App\Models\Interview::create([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'settings'=>['time_limit_per_question'=>120,'allow_retakes'=>true],
            'created_by'=>auth()->id(),
        ]);

        if ($r->ajax()) {
            return response()->json(['redirect'=>route('admin.interviews.edit',$interview)], 201);
        }
        return redirect()->route('admin.interviews.edit',$interview)->with('ok','Interview created');
    }

    public function create(){
        return view('admin.interviews.create');
    }

    

    public function show(Interview $interview){
        return redirect()->route('admin.interviews.edit', $interview);
    }

    public function edit(Interview $interview){
        $interview->load('questions');
        return view('admin.interviews.edit', compact('interview'));
    }

    public function update(Request $r, Interview $interview){
        $data = $r->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'time_limit_per_question'=>'nullable|integer|min:10|max:900',
            'allow_retakes'=>'nullable|boolean',
            'welcome'=>'nullable|string',
            'thanks'=>'nullable|string',
            'due_at'=>'nullable|date',
        ]);
        $settings = $interview->settings ?? [];
        $settings['time_limit_per_question'] = $data['time_limit_per_question'] ?? ($settings['time_limit_per_question'] ?? 120);
        $settings['allow_retakes'] = $data['allow_retakes'] ?? ($settings['allow_retakes'] ?? true);
        $settings['welcome'] = $data['welcome'] ?? ($settings['welcome'] ?? null);
        $settings['thanks'] = $data['thanks'] ?? ($settings['thanks'] ?? null);
        $settings['due_at'] = $data['due_at'] ?? ($settings['due_at'] ?? null);

        $interview->update([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'settings'=>$settings,
        ]);
        return redirect()
            ->route('admin.interviews.edit', $interview)
            ->with('ok', 'Interview updated successfully');
    }

    public function destroy(Interview $interview){
        $interview->delete();
        return redirect()->route('admin.interviews.index')->with('ok','Deleted');
    }
}
