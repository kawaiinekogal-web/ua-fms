<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTicket;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceTicketController extends Controller
{
    private const ACTIVE_STATUSES = ['notified', 'pending', 'ongoing'];

    private const COMPLETED_STATUSES = ['success', 'failed'];

    public function __construct(protected NotificationService $notifications)
    {
    }

    public function index()
    {
        $activeTickets = MaintenanceTicket::with(['requester', 'facility'])
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->latest()
            ->paginate(15, ['*'], 'active_page');

        $completedTickets = MaintenanceTicket::with(['requester', 'facility'])
            ->whereIn('status', self::COMPLETED_STATUSES)
            ->latest('completed_at')
            ->paginate(15, ['*'], 'completed_page');

        return view('admin.maintenance-tickets.index', compact('activeTickets', 'completedTickets'));
    }

    public function show(MaintenanceTicket $ticket)
    {
        $ticket->load(['requester', 'facility']);

        return view('admin.maintenance-tickets.show', compact('ticket'));
    }

    public function update(Request $request, MaintenanceTicket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'ongoing', 'success', 'failed'])],
            'admin_remarks' => ['nullable', 'string', 'max:5000'],
        ]);

        $statusChanged = $ticket->status !== $validated['status'];
        $ticket->status = $validated['status'];
        $ticket->admin_remarks = $validated['admin_remarks'] ?? null;
        $ticket->completed_at = in_array($ticket->status, self::COMPLETED_STATUSES, true) ? now() : null;
        $ticket->save();

        if ($statusChanged && $ticket->requester) {
            $statusLabel = ucfirst($ticket->status);
            $remarks = $ticket->admin_remarks ? " Admin remarks: {$ticket->admin_remarks}" : '';
            $this->notifications->notifyUser(
                $ticket->requester_id,
                'maintenance_ticket_updated',
                "Repair request {$statusLabel}",
                "Your request ({$ticket->ticket_code}) is now {$statusLabel}.{$remarks}",
                ['ticket_id' => $ticket->id, 'ticket_code' => $ticket->ticket_code, 'status' => $ticket->status]
            );
        }

        return redirect()->route('admin.maintenance-tickets.show', $ticket)
            ->with('status', 'Ticket updated.');
    }
}