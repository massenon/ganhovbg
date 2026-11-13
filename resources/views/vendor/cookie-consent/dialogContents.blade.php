@if(file_exists(storage_path('installed')))
<div class="js-cookie-consent cookie-consent gn-cookie" role="dialog" aria-live="polite" aria-labelledby="gn-cookie-t">
    <span class="gn-cookie__ic" aria-hidden="true">🍪</span>
    <p class="gn-cookie__t" id="gn-cookie-t">Nous tenons à votre vie privée</p>
    <p class="gn-cookie__d">Votre expérience sur ce site sera améliorée si vous acceptez les cookies.</p>
    <div class="gn-cookie__a">
        <button type="button" class="gn-cookie__b js-cookie-consent-agree cookie-consent__agree">Accepter</button>
    </div>
</div>
@endif
