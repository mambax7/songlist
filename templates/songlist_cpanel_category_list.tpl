<h1><{$smarty.const._AM_SONGLIST_CATEGORY_H1}></h1>
<p><{$smarty.const._AM_SONGLIST_CATEGORY_P}></p>
<div style="clear:both; height:45px;">
    <div style="float:right; height:45px;"><{$pagenav}></div>
</div>

<form action="<{$php_self}>" method='post'>
<table>
    <tr class="head">
        <th><{$cid_th}></th>
        <th><{$pid_th}></th>
        <th><{$name_th}></th>
        <th><{$artists_th}></th>
        <th><{$albums_th}></th>
        <th><{$songs_th}></th>
        <th><{$hits_th}></th>
        <th><{$rank_th}></th>
        <th><{$created_th}></th>
        <th><{$updated_th}></th>
        <th><{$smarty.const._AM_SONGLIST_TH_ACTIONS}></th>
    </tr>
    <tr class="filter">
        <th>&nbsp;</th>
        <th><{$filter_pid_th}></th>
        <th><{$filter_name_th}></th>
        <th><{$filter_artists_th}></th>
        <th><{$filter_albums_th}></th>
        <th><{$filter_songs_th}></th>
        <th><{$filter_hits_th}></th>
        <th><{$filter_rank_th}></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <{foreach item=item from=$categories}>
    <tr class="<{cycle values="even,odd"}>">
        <td align='center'><{$item.cid}><{$item.form.id}></td>
        <td align='center'><{$item.form.pid}></td>
        <td align='center'><{$item.form.name}></td>
        <td align='center'><{$item.artists}></td>
        <td align='center'><{$item.albums}></td>
        <td align='center'><{$item.songs}></td>
        <td align='center'><{$item.hits}></td>
        <td align='center'><{$item.rank}></td>
        <td align='right'><{$item.form.created}></td>
        <td align='right'><{$item.form.updated}></td>
        <td align='right'><a href="<{$php_self}>?op=category&fct=edit&id=<{$item.cid}>&start=<{$start}>&limit=<{$limit}>&order=<{$order}>&sort=<{$sort}>&filter=<{$filter}>"><{$smarty.const._EDIT}></a>&nbsp;|&nbsp;<a href="<{$php_self}>?op=category&fct=delete&id=<{$item.cid}>&start=<{$start}>&limit=<{$limit}>&order=<{$order}>&sort=<{$sort}>&filter=<{$filter}>"><{$smarty.const._DELETE}></a></td>
    </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="11"><input type='submit' name='submit' value='<{$smarty.const._SUBMIT}>'></td>
    </tr>
</table>
<input type='hidden' name='op' value='category'>
<input type='hidden' name='fct' value='savelist'>
<input type='hidden' name='start' value='<{$start}>'>
<input type='hidden' name='limit' value='<{$limit}>'>
<input type='hidden' name='order' value='<{$order}>'>
<input type='hidden' name='sort' value='<{$sort}>'>
<input type='hidden' name='filter' value='<{$filter}>'>
</form>
<h1><{$smarty.const._AM_SONGLIST_NEW_CATEGORY_H1}></h1>
<p><{$smarty.const._AM_SONGLIST_NEW_CATEGORY_P}></p>
<{$form}>
