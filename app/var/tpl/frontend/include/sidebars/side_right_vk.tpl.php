{if $__ctx->getCity() !== null && $__ctx->getCity()->getVkGroup() !== null}
    <div id="b-left-vk"> </div>

    <script type="text/javascript">
        {literal}
            try {
                VK.Widgets.Group(
                    'b-left-vk',
                    {
                        mode:   0,
                        width:  225,
                        height: 290
                    },
                    {/literal}{$__ctx->getCity()->getVkGroup()|escape} {** ID группы **}{literal}
                );
            } catch (e) {
            }
        {/literal}
    </script>
{/if}

{if $__ctx->getCity() !== null && $__ctx->getCity()->getOdnklGroup() !== null}
<!-- OK Widget -->
<div id="ok_group_widget"></div>
<script type="text/javascript" src="//connect.ok.ru/connect.js?96"></script>
<script type="text/javascript">
    {literal}
    try {
        OK.CONNECT.insertGroupWidget("ok_group_widget", {/literal}'{$__ctx->getCity()->getOdnklGroup()}'{literal}, '{width: "225", height: "290"}');
    } catch (e) {
    }
    {/literal}
</script>
{/if}
{if $__ctx->getCity() !== null && $__ctx->getCity()->getFacebookGroup() !== null}
<br>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.6&appId=109597475838294";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-page" data-href="https://www.facebook.com/{$__ctx->getCity()->getFacebookGroup()}" 
 data-tabs="timeline" data-width="225"
 data-height="300" data-small-header="false" 
 data-adapt-container-width="true" 
 data-hide-cover="false"
 data-show-facepile="true">
 <blockquote cite="https://www.facebook.com/{$__ctx->getCity()->getFacebookGroup()}" 
	class="fb-xfbml-parse-ignore">
 </blockquote></div>

<br>
{/if}

{if $__ctx->getCity() !== null && $__ctx->getCity()->getInstaGroup() !== null}
<br>
<iframe width='225' height='383' src='http://iconosquare.com/widget.php?choice=myfeed&username={$__ctx->getCity()->getInstaGroup()}&show_infos=true&linking=statigram&width=225&height=383&mode=grid&layout_x=3&layout_y=2&padding=10&photo_border=true&background=FFFFFF&text=777777&widget_border=true&radius=5&border-color=DDDDDD&user_id=702118316&time=1421151783130' allowTransparency='true' frameborder='0' scrolling='no' style='border:none; overflow:hidden; width:225px; height:383px;'></iframe>
{/if}


