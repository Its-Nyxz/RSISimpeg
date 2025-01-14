<div {{ $attributes->merge(['class' => 'w-full bg-white border border-gray-200 rounded-lg shadow overflow-hidden ']) }}>
    <div
        class="bg-success-100 flex flex-wrap text-sm rounded-t-lg font-medium text-center text-gray-500 border-b border-gray-20">
    </div>
    <div>
        <div class=" p-5 bg-white rounded-lg ">
            {{ $slot }}
        </div>
    </div>
</div>
