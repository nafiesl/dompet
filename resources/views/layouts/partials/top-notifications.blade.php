@php
    $notifications = auth()->user()->unreadNotifications;
    $diff = $notifications->count() - 5;
@endphp

<div class="dropdown d-flex d-md-none">
    <a class="nav-link icon" href="{{ route('notifications.index') }}" title="{{ __('notification.view_all') }}">
        <i class="fe fe-bell"></i>
        @if ($notifications->count())
            <span class="nav-unread"></span>
        @endif
    </a>
</div>

@if ($notifications->count())
<div class="dropdown d-none d-md-flex">
    <a class="nav-link icon" data-toggle="dropdown">
        <i class="fe fe-bell"></i>
        <span class="nav-unread"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        @foreach($notifications->take(5) as $notification)
            @if (view()->exists('notifications.types.'.get_notification_view_part($notification->type)))
                @include('notifications.types.'.get_notification_view_part($notification->type))
            @endif
        @endforeach
        <div class="dropdown-divider"></div>
        @if ($diff > 0)
            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-muted-dark">
                {{ __('notification.view_other', ['count' => $diff]) }}
            </a>
        @else
            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-muted-dark">
                {{ __('notification.view_all') }}
            </a>
        @endif
    </div>
</div>
@else
<div class="dropdown d-none d-md-flex">
    <a class="nav-link icon" href="{{ route('notifications.index') }}" title="{{ __('notification.view_all') }}">
        <i class="fe fe-bell"></i>
    </a>
</div>
@endif
