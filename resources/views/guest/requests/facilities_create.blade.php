@extends('layouts.guest')

@section('content')
<div class="bg-white rounded shadow p-6">
    <div class="mb-4">
        <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium transition">
            ← Back to Dashboard
        </a>
    </div>

    <h1 class="text-2xl font-semibold mb-4">Facilities Utilization Request</h1>

    <p class="text-gray-700 mb-4">
        Submit a request for facility and equipment usage. Date of request will be recorded automatically after submission.
    </p>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-800 border border-red-200 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('guest.requests.facilities.store') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf

        {{-- Date & Time --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="date_activity" class="block text-sm font-medium text-gray-700">Date of Activity</label>
                <input type="date" name="date_activity" id="date_activity" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white"
                       value="{{ old('date_activity') }}">
            </div>
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" name="start_time" id="start_time" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white"
                       value="{{ old('start_time') }}">
            </div>
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" name="end_time" id="end_time" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white"
                       value="{{ old('end_time') }}">
            </div>
        </div>

        {{-- Facility / Venue --}}
        <div>
            <label for="facility_id" class="block text-sm font-medium text-gray-700">Venue</label>
            <select name="facility_id" id="facility_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white">
                <option value="">Select a facility</option>
                @if(!empty($coreFacilities))
                    @foreach($coreFacilities as $facility)
                        @php
                            $isUnavailable = in_array($facility->availability_status, ['unavailable', 'maintenance'], true);
                            $statusLabel = $facility->availability_status === 'maintenance' ? 'Under Maintenance' : 'Unavailable';
                        @endphp
                        <option
                            value="{{ $facility->id }}"
                            {{ old('facility_id') == $facility->id ? 'selected' : '' }}
                            {{ $isUnavailable ? 'disabled' : '' }}
                            style="{{ $isUnavailable ? 'color:#9CA3AF;' : '' }}"
                        >
                            {{ $facility->name }}{{ $isUnavailable ? ' (' . $statusLabel . ')' : '' }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Noted by (Guest fills custom name) --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Noted by</h2>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person / Organization Representative</label>
            <input type="text" name="noted_signatory_custom"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white"
                   placeholder="Enter the name of your organization representative"
                   value="{{ old('noted_signatory_custom') }}" required>
            <p class="text-xs text-gray-500 mt-1">Enter the name of the person from your organization who is responsible for this request.</p>
        </div>

        {{-- Purpose --}}
        <div>
            <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
            <textarea name="purpose" id="purpose" rows="3" required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white">{{ old('purpose') }}</textarea>
        </div>

        {{-- Equipment --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-2">Facilities / Equipment to be used</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $equipmentFields = [
                        'qty_monobloc' => 'Monobloc Chair',
                        'qty_table'    => 'Table',
                        'qty_fan'      => 'Electric Fan',
                        'qty_rostrum'  => 'Rostrum',
                        'qty_flag'     => 'Flag & School Color',
                        'qty_sound'    => 'Sound',
                        'qty_led'      => 'LED Wall',
                    ];
                @endphp

                @foreach($equipmentFields as $name => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <button type="button" class="px-2 py-1 border rounded text-sm bg-white"
                                    onclick="adjustQty('{{ $name }}', -10)">-10</button>
                            <button type="button" class="px-2 py-1 border rounded text-sm bg-white"
                                    onclick="adjustQty('{{ $name }}', -1)">-1</button>
                            <input type="number" name="{{ $name }}" id="{{ $name }}" min="0"
                                   class="w-20 border border-gray-300 rounded-md py-1 px-2 text-center bg-white"
                                   value="{{ old($name, 0) }}">
                            <button type="button" class="px-2 py-1 border rounded text-sm bg-white"
                                    onclick="adjustQty('{{ $name }}', 1)">+1</button>
                            <button type="button" class="px-2 py-1 border rounded text-sm bg-white"
                                    onclick="adjustQty('{{ $name }}', 10)">+10</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Payment Attachment (Required for Guests) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Receipt <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-600 mb-2">Upload payment receipt (JPG, PNG, or PDF, max 5MB). Payment is required for all guest bookings.</p>
            <input type="file" name="payment_attachment" accept=".jpg,.jpeg,.png,.pdf" required
                   class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm bg-white hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">Your request will be marked as "Pending Payment" until GSU verifies the receipt.</p>
        </div>

        {{-- Attachments (optional) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
            <p class="text-xs text-gray-600 mb-2">Upload supporting documents (PDFs only, max 5-10 files)</p>
            <input type="file" name="attachments[]" multiple accept=".pdf"
                   class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm bg-white hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500"
                   title="Select 5-10 PDF files maximum">
        </div>

        {{-- Submit --}}
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('guest.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 bg-white">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Submit Request
            </button>
        </div>
    </form>
</div>

<script>
function adjustQty(fieldId, delta) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    let value = parseInt(input.value || '0', 10);
    value += delta;
    if (value < 0) value = 0;
    input.value = value;
}

// Prevent double submission
(function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Re-enable after 5 seconds as fallback (in case of validation errors)
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Request';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }, 5000);
        });
    }
})();
</script>
@endsection
