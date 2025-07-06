<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class QuestionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the questions.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'newest');

        $questions = Question::search($search)
                            ->sort($sort)
                            ->withCount('answers')
                            ->with('user')
                            ->paginate(5); // Changed from 10 to 5

        return view('questions.index', compact('questions', 'search', 'sort'));
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        return view('questions.create');
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        $question = new Question();
        $question->title = $request->title;
        $question->content = $request->content;
        $question->user_id = Auth::id();
        $question->status = 'open';
        $question->views = 0;
        $question->save();

        return redirect()->route('questions.index')->with('success', 'Posted successfully!');
    }

    /**
     * Display the specified question.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Question $question): View
    {
        // Increment view count
        $question->increment('views');

        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Question $question): View
    {
        // Allow edit if user owns the question or is admin
        if (!Auth::user()->isAdmin() && $question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        // Allow update if user owns the question or is admin
        if (!Auth::user()->isAdmin() && $question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        $question->title = $request->title;
        $question->content = $request->content;
        $question->save();

        return redirect()->route('questions.show', $question)
                        ->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified question from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Question $question): RedirectResponse
    {
        // Allow delete if user owns the question or is admin
        if (!Auth::user()->isAdmin() && $question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return redirect()->route('questions.index')
                        ->with('success', 'Question deleted successfully.');
    }
}