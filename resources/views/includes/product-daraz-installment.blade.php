@php
    $installments = App\Http\Controllers\FinanceController::calculateAllBanksInstallments($price);
@endphp
<div class="my-4" id="installmentsDaraz">
    <h2 class="fs-2">{{Str::title($product->name)}} Daraz Installments in Pakistan</h2>
    <div class="accordion" id="bankAccordion">
        @foreach ($installments as $bankName => $bankInstallments)
        <div class="accordion-item">
            <h3 class="accordion-header" id="heading{{ $bankName }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $bankName }}" aria-expanded="false" aria-controls="collapse{{ $bankName }}">
                    <img src="{{URL::to('/images/banks/'.$bankName.'.svg')}}" class="me-2" alt="{{$bankName}}"><span class="text-uppercase me-2">{{ $bankName }}</span> Bank Installment Plan
                </button>
            </h3>
            <div id="collapse{{ $bankName }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $bankName }}" data-bs-parent="#bankAccordion">
                <div class="accordion-body">
                  <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Months</th>
                                <th scope="col">Installment</th>
                                <th scope="col">Markup</th>
                                <th scope="col" class="fs-14">Upfront Fee</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bankInstallments as $installment)
                            <tr>
                                <td class="text-center">{{ $installment->tenure }}</td>
                                <td>{{ $installment->monthly_installment }}</td>
                                <td>{{number_format($installment->monthly_markup, 2)}}%</td>
                                <td>{{ $installment->upfront_fee }}</td>
                                <th>{{ $installment->total }}</th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
                    <strong>Processing Fees:</strong>
                    <ul class="list-group mb-3">
                        {!! $bankInstallments[0]->process_fee_list !!}
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
