@extends('layouts.org')

@section('org-content')
<div class="fms-card max-w-3xl">
    <div class="fms-page-header">
        <div>
            <h1 class="fms-page-title">New repair request</h1>
            <p class="text-sm text-neutral-600">Send a message to the facility administrators.</p>
        </div>
        <a href="{{ route('org.maintenance-tickets.index') }}" class="fms-btn-secondary">My requests</a>
    </div>

    @if ($errors->any())
        <div class="mb-5 border border-red-300 bg-red-50 p-3 text-sm text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-5 border-l-4 border-black bg-neutral-50 px-4 py-3 text-sm">
        <p><strong>From:</strong> {{ auth()->user()->name }} ({{ auth()->user()->organization_name ?: 'Organization account' }})</p>
        <p><strong>To:</strong> Facility administrators</p>
    </div>

    <form method="POST" action="{{ route('org.maintenance-tickets.store') }}" class="space-y-5">
        @csrf
        <div>
            <label for="facility_id" class="mb-1 block text-sm font-medium">Facility</label>
            <select id="facility_id" name="facility_id" required class="fms-input">
                <option value="">Choose a facility</option>
                @foreach ($facilities as $facility)
                    <option value="{{ $facility->id }}" @selected(old('facility_id') == $facility->id)>
                        {{ $facility->name }}{{ $facility->location ? ' - ' . $facility->location : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="subject" class="mb-1 block text-sm font-medium">Subject</label>
            <input id="subject" name="subject" type="text" maxlength="255" required class="fms-input"
                   value="{{ old('subject') }}" placeholder="Briefly describe the issue">
        </div>
        <div>
            <label for="issue_description" class="mb-1 block text-sm font-medium">Message</label>
            <textarea id="issue_description" name="issue_description" rows="7" maxlength="10000" required
                      class="fms-input" placeholder="Describe the repair or maintenance needed, including where and when you noticed it.">{{ old('issue_description') }}</textarea>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('org.maintenance-tickets.index') }}" class="fms-btn-secondary">Cancel</a>
            <button type="submit" id="submitBtn" class="fms-btn-primary">Send request</button>
        </div>
    </form>
</div>

<script>
// Prevent double submission
(function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Re-enable after 5 seconds as fallback (in case of validation errors)
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send request';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }, 5000);
        });
    }
})();
</script>
@endsection
