<x-app-layout>
    @section('title', 'Add Transaction')

    <div class="py-6 max-w-2xl">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('transactions.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Transaction</h1>
                <p class="text-sm text-gray-500 mt-0.5">Record a new income or expense</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-5">
                @csrf

                <!-- Type Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all {{ old('type', 'expense') === 'income' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="type" value="income" class="sr-only" {{ old('type', 'expense') === 'income' ? 'checked' : '' }} onchange="this.closest('.grid').querySelectorAll('label').forEach(l=>l.className=l.className.replace('border-green-500 bg-green-50','border-gray-200').replace('border-red-500 bg-red-50','border-gray-200')); this.closest('label').className=this.closest('label').className.replace('border-gray-200','border-green-500 bg-green-50')">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                            <span class="text-sm font-medium text-gray-700">Income</span>
                        </label>
                        <label class="relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all {{ old('type', 'expense') === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="type" value="expense" class="sr-only" {{ old('type', 'expense') === 'expense' ? 'checked' : '' }} onchange="this.closest('.grid').querySelectorAll('label').forEach(l=>l.className=l.className.replace('border-green-500 bg-green-50','border-gray-200').replace('border-red-500 bg-red-50','border-gray-200')); this.closest('label').className=this.closest('label').className.replace('border-gray-200','border-red-500 bg-red-50')">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                            <span class="text-sm font-medium text-gray-700">Expense</span>
                        </label>
                    </div>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Monthly salary, Grocery shopping..." required class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" placeholder="0.00" required class="w-full text-sm border-gray-200 rounded-lg pl-7 pr-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-300 @enderror">
                        </div>
                        @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('transaction_date') border-red-300 @enderror">
                        @error('transaction_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                    <select name="category_id" class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">No category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3" placeholder="Optional notes..." class="w-full text-sm border-gray-200 rounded-lg px-3 py-2.5 focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Add Transaction
                    </button>
                    <a href="{{ route('transactions.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
