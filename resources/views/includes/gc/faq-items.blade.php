{{-- Accordéon natif <details>. Param : $items = [['q','html','locked'], ...] (même rendu que l'accueil : .gh-acc) --}}
<div class="gh-acc gc-faq">
	@foreach ($items as $it)
		<details>
			<summary><h{{ $hl ?? 3 }}>{{ $it['q'] }}</h{{ $hl ?? 3 }}></summary>
			<div class="gh-acc__a">
				@if($it['locked'])
					<p>Vous devez être connecté·e pour accéder à ce contenu. <a href="{{ url('customer/login') }}">Se connecter</a></p>
				@else
					{!! $it['html'] !!}
				@endif
			</div>
		</details>
	@endforeach
</div>
