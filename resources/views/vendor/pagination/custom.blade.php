<!-- resources/views/vendor/pagination/custom.blade.php -->
@if ($paginator->hasPages())
<div class="flex justify-between items-center mt-4">
    <div class="text-sm text-gray-600">
        Showing 
        <span class="font-semibold">{{ $paginator->count() ? ($paginator->perPage() * ($paginator->currentPage() - 1) + 1) : 0 }}</span> 
        to 
        <span class="font-semibold">{{ $paginator->count() ? ($paginator->perPage() * ($paginator->currentPage() - 1) + $paginator->count()) : 0 }}</span> 
        of 
        <span class="font-semibold">{{ $paginator->total() }}</span> 
        results
    </div>

    <div class="flex gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 border rounded hover:bg-gray-100">&laquo;</a>
        @endif

        {{-- Page Links --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="px-3 py-1 border rounded bg-black text-white">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 border rounded hover:bg-gray-100">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 border rounded hover:bg-gray-100">&raquo;</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">&raquo;</span>
        @endif
    </div>
</div>
@endif
