<li>
    @if (!empty($child))
        <button type="button"
            class="flex items-center p-2 w-full text-base font-medium text-white hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100"
            aria-controls="{{ Str::slug($title) }}" data-collapse-toggle="{{ Str::slug($title) }}">
            <i class="{{ $icon }}"></i>
            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <ul id="{{ Str::slug($title) }}" class="hidden py-2 space-y-2">
            @foreach ($child as $item)
                @if (isset($item['child']))
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-medium text-white hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100"
                            aria-controls="{{ Str::slug($item['title']) }}"
                            data-collapse-toggle="{{ Str::slug($item['title']) }}">
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $item['title'] }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <ul id="{{ Str::slug($item['title']) }}" class="hidden py-2 space-y-2">
                            @foreach ($item['child'] as $subItem)
                                <li>
                                    <a href="{{ $subItem['href'] }}"
                                        class="flex items-center p-2 pl-16 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
                                        {{ $subItem['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ $item['href'] }}"
                            class="flex items-center p-2 pl-12 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    @else
        <a href="{{ $href }}"
            class="flex items-center p-2 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
            <i class="{{ $icon }}"></i>
            <span class="ml-3">{{ $title }}</span>
        </a>
    @endif
</li>
