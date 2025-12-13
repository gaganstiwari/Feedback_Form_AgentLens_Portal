<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feedback;

class FeedbackForm extends Component
{
    public $medical_experience = '';
    public $question = '';
    public $checkboxOptions = [];
    public $selectedOptions = [];
    public $subOptions = [];
    public $selectedSubOptions = [];

    public $comment = '';
    public $nps_score = null;
    public $requestid = null;
    public $token_number = null;

    public $feedback_id = null;
    public $formSubmitted = false;

    protected $listeners = [
        'npsSelected' => 'handleNpsClick',
        'policyReceived' => 'setPolicy',
    ];

    public function mount($requestid = null)
    {
        // ⭐ Debug: Log the request_id
        \Log::info('Livewire mount called', [
            'requestid_param' => $requestid,
            'session_requestid' => session('request_id'),
            'query_requestid' => request()->query('request_id')
        ]);

        // Try multiple sources for request_id
        $this->requestid = $requestid
            ?? session('request_id')
            ?? request()->query('request_id');

        if (!$this->requestid) {
            session()->flash('error', 'Request ID is required.');
            \Log::error('Request ID is missing in Livewire mount');
            return;
        }

        \Log::info('Request ID set successfully', ['requestid' => $this->requestid]);

        $draft = Feedback::where('request_id', $this->requestid)->first();

        if ($draft) {
            $this->feedback_id = $draft->id;
            $this->nps_score = $draft->nps_score ?? null;
            $this->comment = $draft->feedback['comment'] ?? '';

            if (isset($draft->feedback['options'])) {
                $this->selectedOptions = array_keys($draft->feedback['options']);
                $this->selectedSubOptions = $draft->feedback['options'];
            }

            if ($this->nps_score !== null) {
                $this->handleNpsClick($this->nps_score);
            }
        }
    }

    public function setPolicy($data)
    {
        $this->requestid = $data['request_id'] ?? null;
    }

    private function normalizeSelectedSubOptions(): array
    {
        $map = [];

        if (!is_array($this->selectedOptions))
            return $map;

        if (!is_array($this->selectedSubOptions)) {
            foreach ($this->selectedOptions as $main)
                $map[$main] = [];
            return $map;
        }

        $keys = array_keys($this->selectedSubOptions);
        $allNumeric = true;
        foreach ($keys as $k) {
            if (!is_int($k) && !ctype_digit((string) $k)) {
                $allNumeric = false;
                break;
            }
        }

        if ($allNumeric) {
            foreach ($this->selectedOptions as $idx => $main) {
                $val = $this->selectedSubOptions[$idx] ?? [];
                $map[$main] = is_array($val) ? $val : ($val === null ? [] : [$val]);
            }
        } else {
            foreach ($this->selectedOptions as $main) {
                $val = $this->selectedSubOptions[$main] ?? [];
                $map[$main] = is_array($val) ? $val : ($val === null ? [] : [$val]);
            }
        }

        return $map;
    }

    private function getCaseStatusFromNps($score)
    {
        if ($score >= 0 && $score <= 6) {
            return 'open';
        } elseif ($score >= 7 && $score <= 10) {
            return 'close';
        }
        return 'wip';
    }

    /**
     * ⭐ FIXED: Auto-save now always sets is_completed to 0 (false)
     */
    private function autoSave()
    {
        if (!$this->requestid)
            return;

        $options = $this->normalizeSelectedSubOptions();

        // ⭐ Store only comment and options in feedback JSON
        $feedbackData = [
            'comment' => $this->comment,
            'options' => $options,
        ];

        $caseStatus = $this->nps_score !== null ? $this->getCaseStatusFromNps($this->nps_score) : 'wip';

        if ($this->feedback_id) {
            $feedback = Feedback::find($this->feedback_id);

            // ⭐ FIXED: Always set is_completed to FALSE (0) during auto-save
            $feedback->update([
                'feedback' => $feedbackData,
                'request_id' => $this->requestid,
                'nps_score' => $this->nps_score,
                'is_completed' => false, // ⭐ Always 0 for drafts
                'status' => $caseStatus,
            ]);
        } else {
            $feedback = Feedback::create([
                'request_id' => $this->requestid,
                'nps_score' => $this->nps_score,
                'is_completed' => false, // ⭐ Always 0 for new drafts
                'status' => $caseStatus,
                'feedback' => $feedbackData,
            ]);
            $this->feedback_id = $feedback->id;
        }
    }

    public function handleNpsClick($score)
    {
        $this->nps_score = $score;

        $this->medical_experience = match (true) {
            $score >= 9 => 'Excellent',
            $score >= 7 => 'Good',
            default => 'Poor',
        };

        $this->question = match ($this->medical_experience) {
            'Excellent' => 'What went perfect for you?',
            'Good' => 'What could have been better?',
            default => 'We are sorry your experience was not ideal. Please tell us what went wrong.',
        };

        // Assign main options based on NPS
        if ($score >= 9) {
            $this->checkboxOptions = [
                "Staff Professionalism",
                "Staff Coordination",
                "Facilities and Services",
                "Hygiene",
                "Call Center Services"
            ];
        } else {
            $this->checkboxOptions = [
                "Staff Professionalism",
                "Staff Coordination",
                "Facilities and Services",
                "Hygiene",
                "Call Center Services",
                "Report Issues",
                "Sample / Phlebotomy Issues",
                "Home Visit Issues",
                "Charges / Billing Issues",
                "Refund Issues",
                "Information Issues",
                "Facility / Service Issues",
                "Fraud / Suspected Fraud",
                "Other"
            ];
        }

        $this->selectedOptions = [];
        $this->selectedSubOptions = [];
        $this->subOptions = [];

        $this->autoSave();
    }

    public function updatedSelectedOptions()
    {
        if ($this->nps_score >= 9) {
            // NPS 9-10 → no sub-options
            $this->subOptions = [];
            $this->selectedSubOptions = [];
        } else {
            // Master sub-option map
            $map = [
                'Staff Professionalism' => ['Rude / Inappropriate behaviour', 'Incorrect / incomplete information', 'Phlebotomy skill issue', 'Phlebotomy protocol not followed'],
                'Staff Coordination' => ['Phlebo reached late / not reached', 'Phlebo attendance / allocation not managed properly', 'Slot not allocated', 'Home visit not performed as per schedule'],
                'Facilities and Services' => ['High waiting time', 'Infrastructure related issue', 'Health check-up denied', 'Non-serviceable radiology area'],
                'Hygiene' => ['Lack of hygiene / cleanliness'],
                'Call Center Services' => ['Incorrect information provided', 'Incomplete guidance'],
                'Report Issues' => ['Auto report issue', 'Data entry error', 'Partial report not received', 'Report delay', 'Sample lost / leaked', 'Mislabelling & identity mix up', 'Sample not given, yet report received', 'Different values within short span', 'Report comparison with competitor'],
                'Sample / Phlebotomy Issues' => ['Phlebotomy skill issue', 'Phlebotomy protocol not followed', 'Sample lost / leaked'],
                'Home Visit Issues' => ['Phlebo reached late / not reached', 'Phlebo attendance / allocation not managed properly', 'Partner denied visit', 'Confirmation communication not received'],
                'Charges / Billing Issues' => ['Overcharged', 'Home visit charges overcharged', 'High test charges'],
                'Refund Issues' => ['Refund missed by finance', 'Refund not initiated', 'Perception issue'],
                'Information Issues' => ['Missing / wrong product information given', 'Incorrect / incomplete information'],
                'Facility / Service Issues' => ['High waiting time', 'Infrastructure related issue', 'Lack of hygiene / cleanliness', 'Health check-up denied', 'Non serviceable radiology area'],
                'Fraud / Suspected Fraud' => ['Sample sent to other lab', 'Potential fraud', 'Fake report', 'Data entry error'],
                'Other' => ['Duplicate', 'Logged by mistake'],
            ];

            $this->subOptions = [];
            foreach ($this->selectedOptions as $opt) {
                if (isset($map[$opt])) {
                    $this->subOptions[$opt] = $map[$opt];
                }
                if (!isset($this->selectedSubOptions[$opt])) {
                    $this->selectedSubOptions[$opt] = [];
                }
            }
        }

        $this->autoSave();
    }

    public function updatedSelectedSubOptions()
    {
        $this->autoSave();
    }
    public function updatedComment()
    {
        $this->autoSave();
    }

    /**
     * ⭐ REMOVED: saveForm() method - no longer needed
     * Submit button now directly calls submit() which handles completion
     */

    /**
     * ⭐ FIXED: Submit now properly sets is_completed to TRUE (1)
     */
    public function submit()
    {
        if ($this->nps_score === null) {
            session()->flash('error', 'Please select an NPS score first.');
            return;
        }

        $options = $this->normalizeSelectedSubOptions();
        $caseStatus = $this->getCaseStatusFromNps($this->nps_score);

        // ⭐ Store only comment and options in feedback JSON
        $finalData = [
            'comment' => $this->comment,
            'options' => $options,
        ];

        if ($this->feedback_id) {
            // ⭐ CRITICAL FIX: Use DB::table for direct update to ensure boolean conversion
            \DB::table('feedbacks')
                ->where('id', $this->feedback_id)
                ->update([
                    'feedback' => json_encode($finalData),
                    'request_id' => $this->requestid,
                    'nps_score' => $this->nps_score,
                    'is_completed' => 1,  // ⭐ Explicitly set to integer 1
                    'status' => $caseStatus,
                    'updated_at' => now(),
                ]);

            \Log::info('Feedback updated via submit', [
                'feedback_id' => $this->feedback_id,
                'is_completed' => 1
            ]);
        } else {
            $feedback = Feedback::create([
                'request_id' => $this->requestid,
                'nps_score' => $this->nps_score,
                'is_completed' => 1,  // ⭐ Use integer 1 instead of boolean true
                'status' => $caseStatus,
                'feedback' => $finalData,
            ]);
            $this->feedback_id = $feedback->id;

            \Log::info('New feedback created via submit', [
                'feedback_id' => $this->feedback_id,
                'is_completed' => 1
            ]);
        }

        $this->formSubmitted = true;
    }

    public function render()
    {
        return view('livewire.feedback-form');
    }
}