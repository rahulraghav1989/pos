<div style="width: 100%;">
	<h3 style="text-align: center;">@php echo date('d-m-Y', strtotime($dateeod[0]->eodDate)) @endphp</h3>
	<table style="width: 100%;" border="1">
		<thead>
			<th>Payment Type</th>
			<th>Expected</th>
			<th>Counted</th>
		</thead>
		<tbody>
			@foreach($dateeod as $dataeod)
			<tr>
				<td>{{$dataeod->eodPaymentType}}</td>
				<td>{{$dataeod->storeReconAmount}}</td>
				<td>{{$dataeod->eodAmount}}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="3">{{$dateeod[0]->eodNote}}</td>
			</tr>
		</tbody>
	</table>
</div>