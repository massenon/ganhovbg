{{-- Fenêtre de discussion flottante de l'assistant (toutes les pages du gabarit allégé, sauf /assistant qui affiche la version pleine page).
     Empilée AU-DESSUS du bouton SOS ; fermée par défaut ; comportement dans js/gn-assistant.js. --}}
@php $gnAsstWin = \App\Services\AssistantEngine::pageConfig(); @endphp
@if($gnAsstWin['enabled'])
<button type="button" class="gn-asst-fab" data-gn-fab aria-haspopup="dialog" aria-expanded="false" aria-controls="gn-asst-win" title="Ouvrir l’assistant GANXÔ">
	<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v11H9l-5 4z"/><path d="M8.5 10h.01M12 10h.01M15.5 10h.01"/></svg>
	<span class="gn-asst-fab__t">Assistant</span>
</button>
<div class="gn-asst-win" id="gn-asst-win" data-gn-window role="dialog" aria-modal="false" aria-labelledby="gn-asst-win-chat-title" hidden>
	@include('includes.gn.assistant', ['asst' => $gnAsstWin, 'gnAsstTag' => 'h2', 'gnAsstMode' => 'window', 'gnAsstUid' => 'gn-asst-win-chat'])
</div>
@endif
