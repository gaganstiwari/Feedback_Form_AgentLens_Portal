<div>

    {{-- ================================
    ✅ THANK YOU PAGE
    ================================= --}}
    @if ($formSubmitted)
        <div class="thank-you-overlay" id="thankYouMessage">
            <div class="thank-you-content">
                <div class="thank-you-icon">✓</div>
                <h2>Thank You!</h2>
                <p>Redirecting you shortly...</p>

                <div class="thank-you-countdown">
                    <span id="countdown">4</span> seconds
                </div>
            </div>
        </div>

        <script>
            (function () {
                let sec = 4;
                const cd = document.getElementById('countdown');
                if (!cd) return;

                const countdownInterval = setInterval(() => {
                    sec--;
                    if (cd) {
                        cd.textContent = sec;
                    }
                    if (sec <= 0) {
                        clearInterval(countdownInterval);
                        window.location.href = "https://callmedilife.com/";
                    }
                }, 1000);
            })();
        </script>
    @endif


    {{-- ================================
    ✅ MAIN FORM
    ================================= --}}
    @if ($question && !$formSubmitted)
        <div class="form-group col-sm-12 mt-4">

            {{-- ⭐ MAIN QUESTION TITLE --}}
            <label class="form-control-label customlabel">
                {{ $question }}
            </label>


            {{-- ================================
            ⭐ MAIN OPTIONS
            ================================= --}}
            <div class="checkbox-container mt-3">
                @foreach ($checkboxOptions as $option)
                    <div class="form-check" wire:key="main-{{ md5($option) }}">

                        <input type="checkbox" class="form-check-input" wire:model.live="selectedOptions" value="{{ $option }}"
                            id="main-{{ md5($option) }}">

                        <label for="main-{{ md5($option) }}" class="form-check-label">
                            {{ $option }}
                        </label>

                    </div>
                @endforeach
            </div>


            {{-- ================================
            ⭐ SUB OPTIONS
            ================================= --}}
            @if (!empty($subOptions))
                <div class="p-3 border rounded bg-light mt-4">
                    <label class="form-control-label customlabel">Please specify:</label>

                    @foreach ($subOptions as $main => $items)

                        @if (in_array($main, $selectedOptions))

                            <div class="mt-3" wire:key="sub-group-{{ md5($main) }}">

                                <strong>{{ $main }}</strong>

                                @foreach ($items as $item)
                                    <div class="form-check ms-3 mt-1" wire:key="sub-{{ md5($main . $item) }}">

                                        <input type="checkbox" class="form-check-input" wire:model.live="selectedSubOptions.{{ $main }}"
                                            value="{{ $item }}" id="sub-{{ md5($main . $item) }}">

                                        <label class="form-check-label" for="sub-{{ md5($main . $item) }}">
                                            {{ $item }}
                                        </label>

                                    </div>
                                @endforeach

                            </div>

                        @endif

                    @endforeach

                </div>
            @endif



            {{-- ================================
            ⭐ COMMENT BOX
            ================================= --}}
            <div class="form-group col-sm-12 mt-4">
                <label class="form-control-label customlabel">Additional comments:</label>

                <textarea class="form-control" rows="3" placeholder="Write your comments here..."
                    wire:model.live="comment"></textarea>
            </div>


            {{-- ================================
            ⭐ SUBMIT BUTTON - FIXED LOGIC
            ================================= --}}
            @php
                $hasMainOptions = !empty($selectedOptions);
                $hasComment = trim($comment) !== '';

                // Show button if user has selected any main option OR typed a comment
                $showButton = $hasMainOptions || $hasComment;

                // Check if sub-options that are shown have been filled
                $subOptionsValid = true;
                if (!empty($subOptions) && $hasMainOptions) {
                    // For each selected main option that has sub-options
                    foreach ($selectedOptions as $main) {
                        if (isset($subOptions[$main]) && !empty($subOptions[$main])) {
                            // Check if at least one sub-option is selected for this main option
                            if (empty($selectedSubOptions[$main])) {
                                $subOptionsValid = false;
                                break;
                            }
                        }
                    }
                }

                // Enable button if:
                // 1. User has only commented (no main options selected) - VALID
                // 2. User has selected main options AND filled required sub-options - VALID
                // 3. User has selected main options with no sub-options required - VALID
                $canSubmit = ($hasComment && !$hasMainOptions) ||
                    ($hasMainOptions && $subOptionsValid);
            @endphp

            @if ($showButton)
                <div class="text-center mt-4">
                    <button class="btn btn-primary" wire:click="submit" @if (!$canSubmit) disabled @endif>
                        Submit
                    </button>

                    @if (!$canSubmit)
                        <small class="d-block text-muted mt-2">
                            @if ($hasMainOptions && !$subOptionsValid)
                                Please select at least one sub-option for your selected categories
                            @else
                                Please select an option or add a comment to submit
                            @endif
                        </small>
                    @endif
                </div>
            @endif

        </div>
    @endif

</div>