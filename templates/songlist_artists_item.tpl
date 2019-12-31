<div class="container">
    <div class="label"><{$artist.name}></div>
    <div class="content"><br>

    <table class="sl_resulttable" cellpadding="3">
        <thead>
        <tr>
            <th class="songid"><{$smarty.const._MN_SONGLIST_RESULTS_SONGID}></th>
            <th><{$smarty.const._MN_SONGLIST_RESULTS_TITLE}></th>
            <th><{$smarty.const._MN_SONGLIST_RESULTS_TRAXNO}></th>
        </tr>
        </thead>
        </tbody>
    <{if $artist.songs_array}>
        <{assign var=songs value=0}>
        <{foreach from=$artist.songs_array item=song}>
        <tr class="<{cycle values="even,odd"}>">
    <{assign var=songs value=$songs+1}>
            <td class="songid">
                <{$song.songid}>
            </td>
            <td>
                <a href="<{$song.url}>"><{$song.title}></a>
            </td>
            <td class="traxno">
                <{$song.traxid}>
            </td>
        </tr>
        <{/foreach}>

    <{/if}>
       </tbody>
    </table>
    </div>
</div>
    <!--

        <tr>
            <{if $artist.artists_array}>
                <td>
                    <{$smarty.const._MN_SONGLIST_artistS}>
                </td>
                <td>
                    <{assign var=artists value=0}>
                    <{foreach from=$artist.artists_array item=artist}>
                    <{assign var=artists value=$artists+1}>
                    <a href="<{$artist.url}>"><{$artist.name}></a><{if $artists < count($artist.artists)}>,&nbsp;<{/if}>
                    <{/foreach}>
                </td>
            <{/if}>
        </tr>

     -->
