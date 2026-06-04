<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SubmitsFacilitiesForm;
use App\Http\Requests\StoreFacilitiesUtilizationRequest;
use App\Models\Facility;
use App\Models\FormSubmission;
use App\Models\Signatory;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    use SubmitsFacilitiesForm;

    public function __construct(protected NotificationService $notifications)
    {
    }

    /**
     * Show the Facilities Utilization online request form.
     */
    public function createFacilities()
    {
        $user = Auth::user();

        // Org can request any active facility (including college-owned AVRs).
        $coreFacilities = Facility::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Filter signatories by the user's organization
        $presidents = Signatory::where('type', 'org_president')
            ->where('is_active', true)
            ->where('unit', $user->organization_name)
            ->orderBy('name')
            ->get();

        $advisers = Signatory::where('type', 'org_adviser')
            ->where('is_active', true)
            ->where('unit', $user->organization_name)
            ->orderBy('name')
            ->get();

        return view('org.requests.facilities_create', compact(
            'coreFacilities',
            'user',
            'presidents',
            'advisers'
        ));;

    }

    /**
     * Store Facilities Utilization request as a JSON form submission.
     *
     * Delegates core creation/notifications to SubmitsFacilitiesForm, then enriches the submission
     * payload with extra "Noted by" fields so we don't lose the signatory feature.
     */
    public function storeFacilities(StoreFacilitiesUtilizationRequest $request)
    {
        $user = Auth::user();

        $notedName = null;
        if ($request->filled('noted_signatory_id') && $request->input('noted_signatory_id') !== 'custom') {
            [$type, $id] = explode(':', $request->input('noted_signatory_id')) + [null, null];
            if ($id) {
                $signatory = Signatory::find($id);
                if ($signatory) {
                    $notedName = $signatory->name;
                }
            }
        } elseif ($request->filled('noted_signatory_custom')) {
            $notedName = $request->input('noted_signatory_custom');
        }

        $notedDatetime = $notedName ? now()->format('Y-m-d H:i:s') : null;

        // handleStoreFacilities signature expects (StoreFacilitiesUtilizationRequest $request, int $facilityId)
        // but the org form route submits without route params, so facilityId is inside the request.
        $facilityId = (int) $request->input('facility_id');
        $response = $this->handleStoreFacilities($request, $facilityId);


        $submission = FormSubmission::where('requester_id', $user->id)
            ->where('type', 'facilities_utilization')
            ->latest('id')
            ->first();

        if ($submission) {
            $payload = $submission->payload ?? [];
            $payload['noted_signatory_name'] = $notedName;
            $payload['noted_datetime']       = $notedDatetime;
            $submission->payload = $payload;
            $submission->save();
        }

        return $response;
    }

    protected function requesterType(): string
    {
        return 'org';
    }

    protected function requesterUnit(): string
    {
        return Auth::user()->organization_name;
    }

    protected function submittedRedirectRoute(): string
    {
        // After submitting a facilities request, go back to "My requests"
        return 'org.requests.facilities.index';
    }

    /**
     * List the current org staff member's requests, with filters.
     */
    public function indexFacilities(Request $request)
    {
        $user = Auth::user();
        $query = FormSubmission::with('requester')
            ->where('type', 'facilities_utilization')
            ->where('requester_id', $user->id)
            ->where('status', '!=', 'reserved');

        // Filter: control number (payload->control_no)
        if ($search = request('search')) {
            $query->whereRaw("JSON_EXTRACT(payload, '$.control_no') LIKE ?", ['%'.$search.'%']);
        }

        // Filter: status
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Filter: activity date (payload->date_activity)
        if ($activityDate = request('activity_date')) {
            $query->whereRaw("JSON_EXTRACT(payload, '$.date_activity') = ?", [$activityDate]);
        }

        // Order: control_no first (non-null), then created_at desc
        $submissions = $query
            ->orderByRaw("CASE WHEN JSON_EXTRACT(payload, '$.control_no') IS NULL THEN 1 ELSE 0 END ASC")
            ->orderByRaw("JSON_EXTRACT(payload, '$.control_no') ASC")
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends(request()->query());

        return view('org.requests.facilities_index', compact('submissions'));
    }

    /**
     * Show a single facilities utilization request for this org staff user.
     */
    public function showFacilities(FormSubmission $submission)
    {
        $user = Auth::user();

        if (
            $submission->type !== 'facilities_utilization' ||
            $submission->requester_id !== $user->id
        ) {
            abort(404);
        }

        $payload = $submission->payload ?? [];
        $facilities = collect();

        if (!empty($payload['facility_ids'])) {
            $facilities = Facility::whereIn('id', $payload['facility_ids'])->get();
        }

        return view('org.requests.facilities_show', compact('submission', 'payload', 'facilities'));
    }

    /**
     * Cancel a pending facilities utilization request (org side).
     */
    public function cancelFacilities(FormSubmission $submission)
    {
        $user = Auth::user();

        if (
            $submission->type !== 'facilities_utilization' ||
            $submission->requester_id !== $user->id
        ) {
            abort(404);
        }

        if ($submission->status !== 'pending') {
            return back()->withErrors([
                'status' => 'Only pending requests can be cancelled.',
            ]);
        }

        $submission->markCancelled();

        return redirect()
            ->route('org.requests.facilities.index')
            ->with('status', 'Request cancelled.');
    }
}
