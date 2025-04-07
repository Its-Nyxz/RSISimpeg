@php
    $currentPath = request()->path();
    $hrefPath = trim(parse_url($href, PHP_URL_PATH), '/');
    $isActive1 = isset($href) && $currentPath === $hrefPath ? 'bg-gray-100 text-success-950' : 'text-white';
    $isOpen1 =
        isset($child) &&
        collect($child)
            ->pluck('href')
            ->contains(function ($link) use ($currentPath) {
                return trim(parse_url($link, PHP_URL_PATH), '/') === $currentPath;
            })
            ? 'block'
            : 'hidden';
@endphp

@if (isset($child) && empty($child))
    <li>
        <a href="{{ $href }}"
            class="flex items-center p-2 pl-6 text-base font-medium rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950 {{ $isActive1 }}">
            <i class="{{ $icon }}"></i>
            <span class="ml-3">{{ $title }}</span>
        </a>
    </li>
@else
    <li>
        <button type="button"
            class="flex items-center p-2 pl-6 w-full text-base font-medium hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100 {{ $isActive1 }}"
            aria-controls="{{ Str::slug($title) }}" data-collapse-toggle="{{ Str::slug($title) }}">
            <i class="{{ $icon }}"></i>
            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
            <i class="fa-solid fa-chevron-down ml-2 group-hover:rotate-180 transition-transform"></i>
        </button>
        <ul id="{{ Str::slug($title) }}" class="py-2 space-y-2 {{ $isOpen1 }}">
            @foreach ($child as $item)
                @php
                    $childPath = trim(parse_url($item['href'], PHP_URL_PATH), '/');
                    $isActive2 = $currentPath === $childPath ? 'bg-gray-100 text-success-950' : 'text-white';

                    $isOpen2 =
                        isset($item['child']) &&
                        collect($item['child'])
                            ->pluck('href')
                            ->contains(function ($subHref) use ($currentPath) {
                                return trim(parse_url($subHref, PHP_URL_PATH), '/') === $currentPath;
                            })
                            ? 'block'
                            : 'hidden';
                @endphp
                <li>
                    @if (!empty($item['child']))
                        <button type="button"
                            class="flex items-center p-2 pl-10 w-full text-base font-medium rounded-lg transition duration-150 group hover:bg-gray-100 hover:text-success-950 {{ $isActive2 }}"
                            aria-controls="{{ Str::slug($item['title']) }}"
                            data-collapse-toggle="{{ Str::slug($item['title']) }}">
                            <span class="flex-1 text-left whitespace-nowrap">{{ $item['title'] }}</span>
                            <i class="fa-solid fa-chevron-down ml-2 group-hover:rotate-180 transition-transform"></i>
                        </button>
                        <ul id="{{ Str::slug($item['title']) }}" class="py-2 space-y-2 {{ $isOpen2 }}">
                            @foreach ($item['child'] as $subItem)
                                @php
                                    $subPath = trim(parse_url($subItem['href'], PHP_URL_PATH), '/');
                                    $isActive3 =
                                        $currentPath === $subPath ? 'bg-gray-100 text-success-950' : 'text-white';
                                @endphp
                                <li>
                                    <a href="{{ $subItem['href'] }}"
                                        class="flex text-left p-2 pl-12 text-base font-medium rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950 {{ $isActive3 }}">
                                        {{ $subItem['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="flex items-center p-2 pl-10 w-full text-base font-medium rounded-lg transition duration-150 group hover:bg-gray-100 hover:text-success-950 {{ $isActive2 }}">
                            <span class="flex-1 text-left whitespace-nowrap">{{ $item['title'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
@endif
