<div><a href="field.php?op=new"><{$smarty.const._ADD}> <{$smarty.const._AM_SONGLIST_FIELDS_FIELD}></a></div>
<form action="field.php" method="post" id="fieldform">
    <table>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_NAME}></th>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_TITLE}></th>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_DESCRIPTION}></th>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_TYPE}></th>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_CATEGORIES}></th>
        <th><{$smarty.const._AM_SONGLIST_FIELDS_WEIGHT}></th>
        <th></th>
        <{foreach item=category from=$fieldcategories}>
            <{foreach item=field from=$category}>
                <tr class="<{cycle values='odd, even'}>">
                    <td><{$field.field_name}></td>
                    <td><{$field.field_title}></td>
                    <td><{$field.field_description}></td>
                    <td><{$field.fieldtype}></td>
                    <td align="center">
                        <{if $field.canEdit}>
                            <select multiple="multiple" name="categories[<{$field.field_id}>][]" size="4" ><{foreach from=$categories item=cate}><option value="<{$cate.cid}>"<{if in_array($cate.cid, $field.cids) }> selected="selected"<{/if}>><{$cate.name}></option><{/foreach}></select>
                        <{/if}>
                    </td>
                    <td align="center">
                        <{if $field.canEdit}>
                            <input type="text" name="weight[<{$field.field_id}>]" size="5" maxlength="5" value="<{$field.field_weight}>">
                        <{/if}>
                    </td>
                    <td>
                        <{if $field.canEdit}>
                            <{foreach from=$categories item=category}>
                            <{if in_array($category.cid, $field.cids) }>
                            <input type="hidden" name="oldcategories[<{$field.field_id}>][<{$category.cid}>]" value="<{$category.cid}>">
                            <{/if}>
                            <{/foreach}>
                            <input type="hidden" name="oldweight[<{$field.field_id}>]" value="<{$field.field_weight}>">
                            <{foreach from=$field.cids item=cid}>
                            <input type="hidden" name="oldcat[<{$field.field_id}>][<{$cid}>]" value="<{$cid}>">
                            <{/foreach}>
                            <input type="hidden" name="field_ids[<{$field.field_id}>]" value="<{$field.field_id}>">
                            <a href="field.php?id=<{$field.field_id}>" title="<{$smarty.const._EDIT}>"><{$smarty.const._EDIT}></a>
                        <{/if}>
                        <{if $field.canDelete}>
                            &nbsp;<a href="field.php?op=delete&amp;id=<{$field.field_id}>" title="<{$smarty.const._DELETE}>"><{$smarty.const._DELETE}></a>
                        <{/if}>
                    </td>
                </tr>
            <{/foreach}>
        <{/foreach}>
        <tr class="<{cycle values='odd, even'}>">
            <td colspan="5">
            </td>
            <td>
                <{$token}>
                <input type="hidden" name="op" value="reorder">
                <input type="submit" name="submit" value="<{$smarty.const._SUBMIT}>">
            </td>
            <td colspan="2">
            </td>
        </tr>
    </table>
</form>
