<h1><{$smarty.const._AM_SONGLIST_REQUESTS_H1}></h1>
<p><{$smarty.const._AM_SONGLIST_REQUESTS_P}></p>
<div style="clear:both; height:45px;">
    <div style="float:right; height:45px;"><{$pagenav}></div>
</div>

<form action="<{$php_self}>" method='post'>
<table>
    <tr class="head">
        <th><{$rid_th}></th>
        <th><{$artist_th}></th>
        <th><{$album_th}></th>
        <th><{$title_th}></th>
        <th><{$lyrics_th}></th>
        <th><{$name_th}></th>
        <th><{$email_th}></th>
        <th><{$songid_th}></th>
        <th><{$created_th}></th>
        <th><{$updated_th}></th>
        <th><{$smarty.const._AM_SONGLIST_TH_ACTIONS}></th>
    </tr>
    <tr class="filter">
        <th>&nbsp;</th>
        <th><{$filter_artist_th}></th>
        <th><{$filter_album_th}></th>
        <th><{$filter_title_th}></th>
        <th><{$filter_lyrics_th}></th>
        <th><{$filter_name_th}></th>
        <th><{$filter_email_th}></th>
        <th><{$filter_songid_th}></th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <{foreach item=item from=$requests}>
    <tr class="<{cycle values="even,odd"}>">
        <td align='center'><{$item.rid}><{$item.form.id}></td>
        <td align='center'><{$item.artist}></td>
        <td align='center'><{$item.album}></td>
        <td align='center'><{$item.title}></td>
        <td align='center'><{$item.lyrics}></td>
        <td align='center'><{$item.name}></td>
        <td align='center'><{$item.email}></td>
        <td align='center'><{$item.form.songid}></td>
        <td align='right'><{$item.form.created}></td>
        <td align='right'><{$item.form.updated}></td>
        <td align='right'><!-- <a href="<{$php_self}>?op=requests&fct=edit&id=<{$item.rid}>&start=<{$start}>&limit=<{$limit}>&order=<{$order}>&sort=<{$sort}>&filter=<{$filter}>"><{$smarty.const._EDIT}></a>&nbsp;|&nbsp; --><a href="<{$php_self}>?op=requests&fct=delete&id=<{$item.rid}>&start=<{$start}>&limit=<{$limit}>&order=<{$order}>&sort=<{$sort}>&filter=<{$filter}>"><{$smarty.const._DELETE}></a></td>
    </tr>
    <{/foreach}>
    <tr class="foot">
        <td colspan="11"><input type='submit' name='submit' value='<{$smarty.const._SUBMIT}>'></td>
    </tr>
</table>
<input type='hidden' name='op' value='requests'>
<input type='hidden' name='fct' value='savelist'>
<input type='hidden' name='start' value='<{$start}>'>
<input type='hidden' name='limit' value='<{$limit}>'>
<input type='hidden' name='order' value='<{$order}>'>
<input type='hidden' name='sort' value='<{$sort}>'>
<input type='hidden' name='filter' value='<{$filter}>'>
</form>
