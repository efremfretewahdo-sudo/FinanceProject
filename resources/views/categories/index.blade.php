<x-app-layout>
    @section('title', 'Categories')

    <div class="py-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <p class="text-sm text-gray-500 mt-1">Organize your transactions by category</p>
            </div>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </a>
        </div>

        @if($categories->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ $category->color }}1a">
                    <svg class="w-6 h-6" style="color: {{ $category->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">{{ $category->name }}</p>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $category->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($category->type) }}
                    </span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('categories.edit', $category) }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Transactions will become uncategorized.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <p class="text-sm font-medium">No categories yet</p>
            <a href="{{ route('categories.create') }}" class="mt-3 text-sm text-indigo-600 hover:underline">Create your first category</a>
        </div>
        @endif
    </div>
</x-app-layout>
