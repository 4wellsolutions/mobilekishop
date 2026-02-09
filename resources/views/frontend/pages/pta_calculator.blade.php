<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-8 dark:bg-slate-900 dark:ring-slate-800">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white text-center mb-6">PTA Tax Calculator</h2>

        <form action="" method="get" id="ptaForm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <select name="brand_id" id="brand_id"
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <option value="">Select Brand</option>
                        @if($brands = App\Models\Brand::all())
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="md:col-span-5">
                    <select name="product_id" id="product_id"
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        <option value="">Select Model</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button
                        class="w-full px-4 py-2.5 bg-primary text-white text-sm font-bold rounded-lg shadow-sm hover:bg-blue-600 transition"
                        type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="taxTable hidden">
        <div
            class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden dark:bg-slate-900 dark:ring-slate-800">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300">PTA Tax on
                            Passport</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300">PTA Tax on CNIC
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="taxOnPassport" class="px-6 py-4 text-center text-2xl font-bold text-primary"></td>
                        <td id="taxOnCNIC" class="px-6 py-4 text-center text-2xl font-bold text-primary"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>