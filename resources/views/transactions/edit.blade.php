<x-app-layout>
    @section('title', 'Edit Transaction')

    <div class="py-6 max-w-2xl">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('transactions.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Transaction</h1>
                <p class="text-sm text-gray-500 mt-0.5">Update transaction details</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all {{ old('type', $transaction->type) === 'income' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="income" class="sr-only" {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                            <span class="text-sm font-medium text-gray-700">Income</span>
                        </label>
                        <label class="relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all {{ old('type', $transaction->type) === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                            <input type="radio" name="type" value="expense" class="sr-only" {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                            <span class="text-sm font-medium text-gray-700">Expense</span>
                        </label>
                    </div>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $transaction->title) }}" required class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0.01" required class="w-full text-sm border-gray-200 rounded-lg pl-7 pr-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->toDateString()) }}" required class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                    <select name="category_id" class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">No category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3" class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('description', $transaction->description) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Update Transaction
                    </button>
                    <a href="{{ route('transactions.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
