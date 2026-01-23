@if(!empty($data))
<div class="col-md-12 my-2 my-md-3">
	@foreach($data as $bank)
	
    <table class="table table-sm" id="installmentsTable">
        <thead>
        	<tr>
        		<td colspan="5" class="bg-blue text-white text-center fw-bold text-uppercase"><h2 class="fs-3">{{$bank["bank"]}} Bank</h2></td>
        	</tr>
          <tr>
            <th scope="col">Months</th>
            <th scope="col">Installment</th>
            <th scope="col">Markup</th>
            <th scope="col" class="fs-14">Upfront Fee</th>
            <th scope="col">Total</th>
          </tr>
        </thead>
        <tbody>
          
          @foreach($bank["installments"] as $installment)
          <tr>
            <td class="text-center">{{$installment->tenure}}</td>
            <td>{{$installment->monthly_installment}}</td>
            <td>{{number_format($installment->monthly_markup,2)}}%</td>
            <td>{{$installment->upfront_fee}}</td>
            <th>{{$installment->total}}</th>
          </tr>
          @php
          $process_fee_list = $installment->process_fee_list;
          @endphp
          @endforeach
        </tbody>
    </table>
    <h4 class="fs-5">Other Details</h4>
    <ul class="list-unstyled">
    	{!! $process_fee_list !!}
    </ul>
@endforeach
</div>
@endif

