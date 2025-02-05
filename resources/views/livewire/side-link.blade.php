@if (empty($child))
    <li>
        <a href="{{ $href }}"
            class="flex items-center p-2 pl-6 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
            <i class="{{ $icon }}"></i>
            <span class="ml-3">{{ $title }}</span>
        </a>
    </li>
@else
    <li>
        <button type="button"
            class="flex items-center p-2 pl-6 w-full text-base font-medium text-white hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100"
            aria-controls="{{ Str::slug($title) }}" data-collapse-toggle="{{ Str::slug($title) }}">
            <i class="{{ $icon }}"></i>
            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
            <i class="fa-solid fa-chevron-down ml-2"></i>
        </button>
        <ul id="{{ $title }}" class="hidden py-2 space-y-2">
            @foreach ($child as $item)
                <li>
                    @if (!empty($item['child'])) <!-- Check if child exists -->
                        <button type="button"
                            class="flex items-center p-2 pl-10 w-full text-base font-medium text-white hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100"
                            aria-controls="{{ Str::slug($item['title']) }}" 
                            data-collapse-toggle="{{ Str::slug($item['title']) }}">
                            {{ $item['title'] }}
                            <i class="fa-solid fa-chevron-down ml-2"></i>
                        </button>
                        <ul id="{{ Str::slug($item['title']) }}" class="hidden py-2 space-y-2">
                            @foreach ($item['child'] as $subItem)
                                <li>
                                    <a href="{{ $subItem['href'] }}"
                                        class="flex text-left p-2 pl-12 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
                                        {{ $subItem['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="flex items-left p-2 pl-10 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
                            {{ $item['title'] }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>

@endif

