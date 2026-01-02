<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // New method to determine case status based on NPS score
    private function getCaseStatusFromNps($score)
    {
        if ($score >= 0 && $score <= 6) {
            return 'open';
        }

        return 'close';
    }

    //
    public function index(Request $request)
    {
        $requestid = $request->query('request_id');

        if (!$requestid) {
            return back()->with('error', 'Request ID is required.');
        }

        // ⭐ FIX: Pass request_id to view with correct variable name
        return view('feedbackform.feedback', [
            'request_id' => $requestid,  // Changed from 'request_id' to 'requestid'
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMIT (STORE)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'request_id' => 'required|string',
            'is_completed' => 'required|string',
            'nps_score' => 'required|integer|min:0|max:10',
            'main_option' => 'nullable|array',
            'comment' => 'nullable|string',
            'selectedSubOptions' => 'nullable|array',
        ]);

        $mainOptions = $request->input('main_option', []);
        $subOptions = $request->input('selectedSubOptions', []);

        $options = [];
        foreach ($mainOptions as $main) {
            $options[$main] = $subOptions[$main] ?? [];
        }

        // ⭐ Apply NPS score logic
        $caseStatus = $this->getCaseStatusFromNps($request->nps_score);

        // ⭐ Store only comment and options in feedback JSON
        Feedback::create([
            'request_id' => $request->request_id,
            'nps_score' => $request->nps_score,
            'is_completed' => $request->is_completed,
            'status' => $caseStatus,
            'comment' => $request->comment,
            'feedback' => [
                'options' => $options,
            ],
        ]);

        return back()->with('success', 'Feedback submitted successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOSAVE - SAVE EACH CLICK
    |--------------------------------------------------------------------------
    */
    public function autosave(Request $request)
    {
        if (!$request->request_id) {
            return response()->json(['error' => 'Request ID is missing'], 400);
        }

        $feedback = Feedback::firstOrNew([
            'request_id' => $request->request_id
        ]);

        $mainOptions = $request->input('main_option', []);
        $subOptions = $request->input('selectedSubOptions', []);

        $options = [];
        foreach ($mainOptions as $main) {
            $options[$main] = $subOptions[$main] ?? [];
        }

        // ⭐ Apply NPS score logic (only if provided)
        if (!is_null($request->nps_score)) {
            $feedback->nps_score = $request->nps_score;
            $feedback->status = $this->getCaseStatusFromNps($request->nps_score);
        }

        // ⭐ Store only comment and options in feedback JSON
        $feedback->comment = $request->comment;
        $feedback->feedback = [
            'options' => $options,
        ];

        $feedback->is_completed = 0;
        $feedback->save();

        return response()->json([
            'saved' => true,
            'data' => $feedback->feedback
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE FEEDBACK (Final Update API)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|string',
            'nps_score' => 'nullable|integer|min:0|max:10',
            'comment' => 'nullable|string',
            'is_completed' => 'nullable|string',
            'options' => 'nullable|array',
        ]);

        $feedback = Feedback::where('request_id', $validated['request_id'])->first();

        if (!$feedback) {
            return response()->json([
                'is_completed' => 0,
                'message' => 'Feedback record not found'
            ], 404);
        }

        // ⭐ Apply NPS score logic when updated
        if (isset($validated['nps_score'])) {
            $feedback->nps_score = $validated['nps_score'];
            $feedback->status = $this->getCaseStatusFromNps($validated['nps_score']);
        }

        // Update status if provided
        if (isset($validated['is_completed'])) {
            $feedback->is_completed = $validated['is_completed'];
        }

        // ⭐ Store only comment and options in feedback JSON
        $feedback->comment = $validated['comment'] ?? ($feedback->feedback['comment'] ?? null);

        $feedback->feedback = [
            'options' => $validated['options'] ?? ($feedback->feedback['options'] ?? []),
        ];

        $feedback->save();

        return response()->json([
            'is_completed' => true,
            'message' => 'Feedback updated successfully',
            'data' => $feedback->feedback
        ]);
    }
}