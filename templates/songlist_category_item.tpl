<{if $category.picture}>
<div class="sl_maincatpic">
    <a href="<{$category.url}>"><img src="<{$category.picture}>" width='90%'></a>
</div>
<{/if}>
<div class="sl_maincat">
    <a href="<{$category.url}>"><{$category.name}></a><{if $category.parenturl}> <a href="<{$category.parenturl}>"><em><{$smarty.const._MN_SONGLIST_GOTOPARENT}></em></a><{/if}>
</div>
<{if $category.subcategories}>
<div class="sl_subcat">
    <{assign var=cats value=0}>
    <{foreach from=$category.subcategories item=subcategory}>
    <{assign var=cats value=$cats+1}>
    <a href="<{$subcategory.url}>"><{$subcategory.name}></a><{if $cats < count($category.subcategories)}>,&nbsp;<{/if}>
    <{/foreach}>
</div>
<{/if}>
