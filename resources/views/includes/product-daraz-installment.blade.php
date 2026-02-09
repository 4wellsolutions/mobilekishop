@php
    $installments = App\Http\Controllers\FinanceController::calculateAllBanksInstallments($price);
@endphp
<div class="my-6" id="installmentsDaraz">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ Str::title($product->name) }} Daraz
        Installments in Pakistan</h2>
    <div class="space-y-3">
        @foreach ($installments as $bankName => $bankInstallments)
            <div
                class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden dark:bg-slate-900 dark:ring-slate-800">
                <button type="button"
                    class="w-full flex items-center gap-3 px-5 py-3.5 text-left hover:bg-slate-50 dark:hover:bg-slate-800/50 transition"
                    onclick="var body=this.nextElementSibling; var icon=this.querySelector('.toggle-icon'); body.classList.toggle('hidden'); icon.textContent=body.classList.contains('hidden')?'expand_more':'expand_less';">
                    <img src="{{ URL::to('/images/banks/' . $bankName . '.svg') }}" class="h-6 w-auto" alt="{{ $bankName }}">
                    <span class="text-sm font-bold text-slate-900 dark:text-white uppercase">{{ $bankName }}</span>
                    <span class="text-sm text-slate-500">Bank Installment Plan</span>
                    <span
                        class="material-symbols-outlined text-[20px] text-slate-400 ml-auto toggle-icon">expand_more</span>
                </button>
                <div class="hidden border-t border-slate-200 dark:border-slate-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-700 dark:text-slate-300">Months
                                    </th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-700 dark:text-slate-300">
                                        Installment</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-700 dark:text-slate-300">Markup
                                    </th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-700 dark:text-slate-300">Upfront
                                        Fee</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-700 dark:text-slate-300">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($bankInstallments as $installment)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                        <td class="px-4 py-2.5 text-center font-medium text-slate-900 dark:text-white">
                                            {{ $installment->tenure }}</td>
                                        <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">
                                            {{ $installment->monthly_installment }}</td>
                                        <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">
                                            {{ number_format($installment->monthly_markup, 2) }}%</td>
                                        <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">
                                            {{ $installment->upfront_fee }}</td>
                                        <td class="px-4 py-2.5 font-bold text-slate-900 dark:text-white">
                                            {{ $installment->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800">
                        <strong class="text-sm text-slate-700 dark:text-slate-300">Processing Fees:</strong>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mt-1 prose prose-sm max-w-none">
                            {!! $bankInstallments[0]->process_fee_list !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>