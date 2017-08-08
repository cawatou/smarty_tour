{$__ctx->addJs('/base.js')}

{$__ctx->addJs('/bootstrap-typeahead.js')}

{$__ctx->addJs('/suggest.js')}

{$__ctx->addJs('/autocomplete.js')}

<div class="search-hotel-tours">
    <span class="search-hotel-tours-title">Поиск отеля:</span>
    <input type="text" id="hotel_seach_tour" name="hotel_seach_tour" placeholder="Введите первые буквы отеля на английском языке" data-href="{$__url->url('/hotel/')}" />
</div>