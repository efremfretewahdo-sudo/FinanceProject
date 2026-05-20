<x-app-layout>
    @section('title', 'Edit Category')

    <div class="py-6 max-w-lg">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                <p class="text-sm text-gray-500 mt-0.5">Update category details</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer {{ old('type', $category->type) === 'income' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="income" class="sr-only" {{ old('type', $category->type) === 'income' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Income</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer {{ old('type', $category->type) === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="expense" class="sr-only" {{ old('type', $category->type) === 'expense' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Expense</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="{{ old('color', $category->color) }}" class="w-10 h-10 rounded-lg border-gray-200 cursor-pointer p-0.5">
                        <span class="text-sm text-gray-500">Choose a color to identify this category</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Update Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
