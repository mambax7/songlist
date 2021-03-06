<div class="container">
    <div class="label"> <{$smarty.const._MN_SONGLIST_SONGID}><{$song.songid}></div>
        <div class="content"><br>
             <table class="sl_resulttable" cellpadding="3">
                 <{if $song.album.picture}>
                 <tr>
                    <td align="center" colspan="2">
                         <div class="sl_artsong">
                            <a href="<{$song.album.url}>">
                                <img src='<{$song.album.picture}>' width="95%" border="0">
                             </a>
                         </div>
                    </td>
                 </tr>
                 <{else}>
                     <{if $song.category.picture}>
                 <tr>
                    <td align="center" colspan="2">
                         <div class="sl_artsong">
                             <img src='<{$song.category.picture}>' width="95%" border="0">
                         </div>
                    </td>
                 </tr>
                     <{/if}>
                 <{/if}>
                <tr class="<{cycle values="even,odd"}>">
                    <td class="leftcol">
                        <{$smarty.const._MN_SONGLIST_TRAXID}>
                    </td>
                    <td>
                        <{$song.traxid}>
                    </td>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <td class="leftcol">
                        <{$smarty.const._MN_SONGLIST_TITLE}>
                    </td>
                    <td>
                        <{$song.title}>
                    </td>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <td class="leftcol">
                        <{$smarty.const._MN_SONGLIST_LYRICS}>
                    </td>
                    <td>
                        <{$song.lyrics}>
                    </td>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <{if $song.artists_array}>
                        <td class="leftcol">
                            <{if $song.artists_array|is_array && count($song.artists_array) > 0 }>
                                <{$smarty.const._MN_SONGLIST_ARTISTS}>
                            <{else}>
                                <{$smarty.const._MN_SONGLIST_ARTIST}>
                            <{/if}>
                        </td>
                        <td>
                            <{assign var=artists value=0}>
                            <{foreach from=$song.artists_array item=artist}>
                            <{assign var=artists value=$artists+1}>
                            <a href="<{$artist.url}>"><{$artist.name}></a><{if $artists < count($song.artists_array)}>,&nbsp;<{/if}>
                            <{/foreach}>
                        </td>
                    <{/if}>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <{if $song.genre}>
                        <td class="leftcol">
                            <{$smarty.const._MN_SONGLIST_GENRE}>
                        </td>
                        <td>
                            <{$song.genre}>
                        </td>
                    <{/if}>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <{if $song.voice}>
                        <td class="leftcol">
                            <{$smarty.const._MN_SONGLIST_VOICE}>
                        </td>
                        <td>
                            <{$song.voice.name}>
                        </td>
                    <{/if}>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <{if $song.album}>
                        <td class="leftcol">
                            <{$smarty.const._MN_SONGLIST_ALBUM}>
                        </td>
                        <td>
                            <a href="<{$song.album.url}>"><{$song.album.title}></a>
                        </td>
                    <{/if}>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                    <{if $song.category}>
                        <td class="leftcol">
                            <{$smarty.const._MN_SONGLIST_CATEGORY}>
                        </td>
                        <td>
                            <a href="<{$song.category.url}>">
                                <{$song.category.name}>
                            </a>
                        </td>
                    <{/if}>
                </tr>
                <tr class="<{cycle values="even,odd"}>">
                     <{if $xoConfig.tags}>
                        <td colspan="2">
                            <{include file="db:tag_bar.tpl" tagbar=$song.tagbar}>
                        </td>
                    <{/if}>
                 </tr>
                 <tr class="<{cycle values="even,odd"}>">
                    <{foreach from=$song.fields item=field}>
                        <td class="leftcol">
                            <{$field.title}>
                        </td>
                        <td>
                            <{$field.value}>
                        </td>
                    <{/foreach}>
                </tr>
                <{if $song.mp3}>
                <tr class="<{cycle values="even,odd"}>">
                    <td colspan="2" align="center">
                        <{$song.mp3}>
                    </td>
                </tr>
                <{/if}>
            </table>
        </div>
    </div>
