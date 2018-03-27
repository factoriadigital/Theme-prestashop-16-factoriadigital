{if isset($nbComments) && $nbComments > 0}
<div class="productRatings clearfix">
{if $empty_grade==0}
		{section name="i" start=0 loop=5 step=1}
			{if $average_total le $smarty.section.i.index}
				<div class="star"></div>
			{else}
				<div class="star star_on"></div>
			{/if}
		{/section}
	{/if}
    {if $empty_grade==1}
    	{section name="j" start=0 loop=5 step=0}
    	<div class="star"></div>
        {/section}
	{/if}
 <!--  <span class="nb-comments"><span itemprop="reviewCount">{$nbComments}</span> {l s='Review(s)' mod='productcomments'}</span>-->
	</div>{/if}