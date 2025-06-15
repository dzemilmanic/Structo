<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class AnswerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created answer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Question $question): RedirectResponse
    {
        $request->validate([
            'content' => 'required|min:2',
            'question_id' => 'required|exists:questions,id',
        ]);

        $answer = new Answer();
        $answer->content = $request->content;
        $answer->user_id = Auth::id();
        $answer->question_id = $question->id;
        $answer->is_solution = false;
        $answer->save();

        return redirect()->route('questions.show', $question)
                        ->with('success', 'Your answer has been added successfully.');
    }

    /**
     * Show the form for editing the specified answer.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Answer $answer): View
    {
        $this->authorize('update', $answer);

        $question = $answer->question;

        return view('answers.edit', compact('answer', 'question'));
    }

    /**
     * Update the specified answer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Answer $answer): RedirectResponse
    {
        $this->authorize('update', $answer);

        $request->validate([
            'content' => 'required|min:5',
        ]);

        $answer->content = $request->content;
        $answer->save();

        return redirect()->route('questions.show', $answer->question)
                        ->with('success', 'Response updated successfully.');
    }

    /**
     * Remove the specified answer from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Answer $answer): RedirectResponse
    {
        $this->authorize('delete', $answer);

        $question = $answer->question;
        $answer->delete();

        return redirect()->route('questions.show', $question)
                        ->with('success', 'The answer was successfully deleted..');
    }
    public function index()
{
    // Ako ne koristiš, možeš samo vratiti 404 ili neku poruku
    abort(404);
}

    /**
     * Mark an answer as solution.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsSolution(Answer $answer): RedirectResponse
    {
        $question = $answer->question;

        $this->authorize('markSolution', $question);

        // Reset all answers for this question
        Answer::where('question_id', $question->id)
              ->update(['is_solution' => false]);

        // Mark this answer as solution
        $answer->is_solution = true;
        $answer->save();

        // Update question status
        $question->status = 'resolved';
        $question->save();

        return redirect()->route('questions.show', $question)
                        ->with('success', 'The answer is marked as a solution.');
    }
}