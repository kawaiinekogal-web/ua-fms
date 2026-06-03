@php
    $portalTitle = $portalTitle ?? 'Portal';
    $portalSubtitle = $portalSubtitle ?? null;
    $sections = $sections ?? [];
    $footer = $footer ?? null;
@endphp

<aside class="fms-sidebar" aria-label="Main navigation">
    <div class="fms-sidebar-header">
        <p class="fms-sidebar-title">{{ $portalTitle }}</p>
        @if ($portalSubtitle)
            <p class="fms-sidebar-subtitle">{{ $portalSubtitle }}</p>
        @endif
    </div>

    {{-- NAV GROUPS --}}
    <nav class="fms-sidebar-nav">
        @foreach ($sections as $section)
            <div class="fms-nav-group">
                @if (!empty($section['heading']))
                    <p class="fms-nav-group-title">{{ $section['heading'] }}</p>
                @endif

                @if (!empty($section['links']))
                    <ul class="fms-nav-list">
                        @foreach ($section['links'] as $link)
                            @php
                                $route    = $link['route']  ?? null;
                                $patterns = (array)($link['routes'] ?? $route);
                                $isActive = false;
                                foreach ($patterns as $pattern) {
                                    if ($pattern && request()->routeIs($pattern)) {
                                        $isActive = true;
                                        break;
                                    }
                                }
                            @endphp
                            <li>
                                <a href="{{ $route ? route($route) : '#' }}"
                                   class="fms-nav-link {{ $isActive ? 'fms-nav-link-active' : '' }}">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </nav>

    <div class="mt-auto border-t border-black p-4 text-sm flex flex-col gap-4 bg-white">
        @if ($footer)
            <div class="leading-tight text-neutral-800">
                {!! $footer !!}
            </div>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="text-left w-full text-black font-medium hover:underline flex items-center">
                Logout
            </button>
        </form>
    </div>
</aside>