@php
    $currentPath = request()->path();
    $hrefPath = trim(parse_url($href, PHP_URL_PATH), '/');
    $isActive1 = isset($href) && $currentPath === $hrefPath ? 'bg-gray-100 text-success-950' : 'text-white';

    // Cek apakah salah satu child atau subchild aktif
    $flattenedChildren = collect($child ?? [])->flatMap(function ($item) {
        return isset($item['child'])
            ? array_merge(
                [$item['href']],
                collect($item['child'])
                    ->pluck('href')
                    ->toArray(),
            )
            : [$item['href']];
    });

    $isOpen1 =
        isset($child) &&
        $flattenedChildren->contains(function ($link) use ($currentPath) {
            return trim(parse_url($link, PHP_URL_PATH), '/') === $currentPath;
        });
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
    <li x-data="{ open: {{ $isOpen1 ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center p-2 pl-6 w-full text-base font-medium hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100 {{ $isOpen1 ? 'bg-gray-100 text-success-950' : 'text-white' }}">
            <i class="{{ $icon }}"></i>
            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid ml-2 transition-transform"></i>
        </button>

        <ul x-show="open" x-transition class="py-2 space-y-2">
            @foreach ($child as $item)
                @php
                    $childPath = trim(parse_url($item['href'], PHP_URL_PATH), '/');
                    $subPaths = collect($item['child'] ?? [])
                        ->pluck('href')
                        ->map(function ($url) {
                            return trim(parse_url($url, PHP_URL_PATH), '/');
                        });

                    $isActive2 =
                        $currentPath === $childPath || $subPaths->contains($currentPath)
                            ? 'bg-gray-100 text-success-950'
                            : 'text-white';

                    $isOpen2 = $subPaths->contains($currentPath);
                @endphp
                <li x-data="{ open: {{ $isOpen2 ? 'true' : 'false' }} }">
                    @if (!empty($item['child']))
                        <button @click="open = !open"
                            class="flex items-center p-2 pl-10 w-full text-base font-medium rounded-lg transition duration-150 group hover:bg-gray-100 hover:text-success-950 {{ $isActive2 }}">
                            <span class="flex-1 text-left whitespace-nowrap">{{ $item['title'] }}</span>
                            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                                class="fa-solid ml-2 transition-transform"></i>
                        </button>

                        <ul x-show="open" x-transition class="py-2 space-y-2">
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
