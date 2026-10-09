@extends('layouts.app')

@section('content')
    <div class="fms-card">
        <div class="fms-page-header">
            <div>
                <h1 class="fms-page-title">Notifications</h1>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="fms-btn-secondary">
                    ← Back
                </a>
            </div>
        </div>

        @if($notifications->count())
            <div class="fms-table-wrap">
                <table class="fms-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>When</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                            <tr id="notification-{{ $notification->id }}" class="{{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}">
                                <td>
                                    @if($notification->is_read)
                                        <span class="fms-badge" style="background-color: #e5e7eb; color: #374151;">Read</span>
                                    @else
                                        <span id="badge-{{ $notification->id }}" class="fms-badge">New</span>
                                    @endif
                                </td>
                                <td id="title-{{ $notification->id }}" class="{{ $notification->is_read ? 'font-normal' : 'font-semibold' }}">{{ $notification->title }}</td>
                                <td>{{ Str::limit($notification->message, 80) }}</td>
                                <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if(!$notification->is_read)
                                        <button type="button" id="btn-{{ $notification->id }}" onclick="markAsRead({{ $notification->id }}, event)" class="fms-link">Mark as read</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <p class="text-sm text-neutral-600">No notifications yet.</p>
        @endif
    </div>

    <script>
        function markAsRead(notificationId, event) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Change background to white
                    const row = document.getElementById(`notification-${notificationId}`);
                    row.classList.remove('bg-gray-100');
                    row.classList.add('bg-white');

                    // Change badge to "Read"
                    const badge = document.getElementById(`badge-${notificationId}`);
                    badge.textContent = 'Read';
                    badge.className = 'fms-badge';
                    badge.style.backgroundColor = '#e5e7eb';
                    badge.style.color = '#374151';

                    // Remove bold from title
                    const title = document.getElementById(`title-${notificationId}`);
                    title.classList.remove('font-semibold');
                    title.classList.add('font-normal');

                    // Remove the button
                    event.target.remove();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to mark notification as read. Please try again.');
            });
        }
    </script>
@endsection