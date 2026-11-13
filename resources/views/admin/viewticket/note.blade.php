@foreach ($ticket->ticketnote()->latest()->get() as $note)

	<!-- Note List-->
	<div class="alert alert-light-warning note" role="alert">
		<p class="m-0"><b>{{lang('Note:-')}}</b>{{$note->ticketnotes}}</p>
	</div>
	<!--End Note List-->

@endforeach
