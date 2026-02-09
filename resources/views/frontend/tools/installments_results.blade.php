@if(!empty($data))
  <div class="space-y-8 my-6">
    @foreach($data as $bank)
      <div
        class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden dark:bg-slate-900 dark:ring-slate-800">
        <div class="bg-primary px-6 py-3">
          <h2 class="text-lg font-bold text-white text-center uppercase">{{ $bank["bank"] }} Bank</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm" id="installmentsTable">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-800">
                <th class="px-4 py-3 text-left font-bold text-slate-700 dark:text-slate-300">Months</th>
                <th class="px-4 py-3 text-left font-bold text-slate-700 dark:text-slate-300">Installment</th>
                <th class="px-4 py-3 text-left font-bold text-slate-700 dark:text-slate-300">Markup</th>
                <th class="px-4 py-3 text-left font-bold text-slate-700 dark:text-slate-300">Upfront Fee</th>
                <th class="px-4 py-3 text-left font-bold text-slate-700 dark:text-slate-300">Total</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
              @foreach($bank["installments"] as $installment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                  <td class="px-4 py-3 text-center font-medium text-slate-900 dark:text-white">{{ $installment->tenure }}</td>
                  <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $installment->monthly_installment }}</td>
                  <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                    {{ number_format($installment->monthly_markup, 2) }}%</td>
                  <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $installment->upfront_fee }}</td>
                  <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $installment->total }}</td>
                </tr>
                @php
                  $process_fee_list = $installment->process_fee_list;
                @endphp
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
          <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Other Details</h4>
          <div class="text-sm text-slate-600 dark:text-slate-400 prose prose-sm max-w-none">
            {!! $process_fee_list !!}
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif