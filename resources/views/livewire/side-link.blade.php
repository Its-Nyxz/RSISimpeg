@if (!count($child) > 0)
    <li>
        <a href="{{ $href }}"
            class="flex items-center p-2 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
            <i class="{{ $icon }}"></i>
            <span class="ml-3">{{ $title }}</span>
        </a>
    </li>
@else
    <li>
        <button type="button"
            class="flex items-center p-2 w-full text-base font-medium text-white hover:text-success-950 rounded-lg transition duration-150 group hover:bg-gray-100"
            aria-controls="{{ $title }}" data-collapse-toggle="{{ $title }}">
            <i class="{{ $icon }}"></i>
            <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <ul id="{{ $title }}" class="hidden py-2 space-y-2">
            @foreach ($child as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                        class="flex items-center p-2 pl-12 text-base font-medium text-white rounded-lg hover:bg-gray-100 transition duration-150 hover:text-success-950">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
@endif
