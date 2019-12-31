<div class="sl_search">
    <div class="sl_searchdiv">
        <form action="<{$php_self}>" method='GET'>
        <input type="hidden" name="op" value="search">
        <table width="100%" border='0' cellpadding="5">
            <tr>
                <td class="head"><{$smarty.const._MN_SONGLIST_FILTER_SEARCHBY}>:&nbsp;
                <select id='fct' name='fct'>
                    <option value="albums"<{if $smarty.get.fct eq 'albums'}> selected="selected"<{/if}>>
                        <{$smarty.const._MN_SONGLIST_FILTER_ALBUM}>
                    </option>
                    <option value="artists"<{if $smarty.get.fct eq 'artists'}> selected="selected"<{/if}>>
                        <{$smarty.const._MN_SONGLIST_FILTER_ARTIST}>
                    </option>
                    <option value="lyrics"<{if $smarty.get.fct eq 'lyrics'}> selected="selected"<{/if}>>
                        <{$smarty.const._MN_SONGLIST_FILTER_LYRICS}>
                    </option>
                    <option value="title"<{if $smarty.get.fct eq 'title' or $smarty.get.fct eq '' or $smarty.get.fct eq 'list'}> selected="selected"<{/if}>>
                        <{$smarty.const._MN_SONGLIST_FILTER_TITLE}>
                    </option>
                </select>
                </td>
            </tr>
            <tr>
                <td class="head"><input type="textbox" max="128" size="35" name="value" id="value" value="<{$smarty.get.value}>"></td>
            </tr>
            <tr>
                <td class="head"><{$smarty.const._MN_SONGLIST_FILTER_CATEGORY}>:&nbsp;<{$category_element}></td>
            </tr>

            <{if $xoConfig.genre}>
                <tr>
                    <td class="head"><{$smarty.const._MN_SONGLIST_FILTER_GENRE}>:&nbsp;<{$genre_element}></td>
                </tr>
            <{else}>
                    <input type="hidden" name="genre" value="0">
            <{/if}>

            <{if $xoConfig.voice}>
            <tr>
                <td class="head"><{$smarty.const._MN_SONGLIST_FILTER_VOICE}>:&nbsp;<{$voice_element}></td>
            </tr>
            <{else}>
                <input type="hidden" name="voice" value="0">
            <{/if}>
            <tr>
                <td class="foot"><input type="submit" name="submit" value="<{$smarty.const._SUBMIT}>"></td>
            </tr>
        </table>
        </form>
    </div>
</div>
<div class='sl_sep'>&nbsp;</div>
