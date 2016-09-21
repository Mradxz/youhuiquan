<div class="swiper-container" style="width: 100%;">
<div class="swiper-wrapper">
<volist name="ad_list" id="ad">
<div class="swiper-slide"><a rel="external" href="{$ad.url}"><img style="width: 100%;" src="{:attach($ad['content'],'banner')}" alt="{$ad.name}" title="{$ad.desc}"/></a>
</div>
</volist>
</div>
</div>