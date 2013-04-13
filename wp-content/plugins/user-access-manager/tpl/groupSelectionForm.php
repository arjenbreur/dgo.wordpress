<?php
/**
 * groupSelectionForm.php
 * 
 * Shows the selection form.
 * 
 * PHP versions 5
 * 
 * @category  UserAccessManager
 * @package   UserAccessManager
 * @author    Alexander Schneider <alexanderschneider85@googlemail.com>
 * @copyright 2008-2010 Alexander Schneider
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 * @version   SVN: $Id$
 * @link      http://wordpress.org/extend/plugins/user-access-manager/
 */
?>
<input type="hidden" name="uam_update_groups" value="true" />
<ul class="uam_group_selection">
<?php
if (!isset($groupsFormName)
    || $groupsFormName === null
) {
    $groupsFormName = 'uam_usergroups';
}


global $current_user;
wp_get_current_user();

    
foreach ($uamUserGroups as $uamUserGroup) {
    $addition = '';
    $attributes = '';
    
//    // select group if it has only one user and that user is the current user
//    $is_current_user = false;
//    $uam_group_users = $uamUserGroup->getFullUsers();
//    if(count($uam_group_users)==1){
//        $single_user = array_pop($uam_group_users);
//        $is_current_user = ( $current_user->ID == $single_user->id ) ? true:false;
//    }
//    if ($is_current_user ) {
//        $attributes .= 'disabled="disabled" ';
//    }
//    if ($is_current_user || array_key_exists($uamUserGroup->getId(), $userGroupsForObject) ) {
//        $attributes .= 'checked="checked" ';
//    }
    if (array_key_exists($uamUserGroup->getId(), $userGroupsForObject) ) {
        $attributes .= 'checked="checked" ';
    }
    
    if (isset($userGroupsForObject[$uamUserGroup->getId()]->setRecursive[$objectType][$objectId])) {
        $attributes .= 'disabled="" ';
		$addition .= ' [LR]';
	}

	?>
    <li class="<?php echo (substr($uamUserGroup->getGroupName(), 0, 1)=='_')? 'dgo-group':'dgo-single-user-group';?>" >
        <label for="<?php echo $groupsFormName; ?>-<?php echo $uamUserGroup->getId(); ?>" class="selectit" style="display:inline;" >
			<input type="checkbox" id="<?php echo $groupsFormName; ?>-<?php echo $uamUserGroup->getId(); ?>" <?php echo $attributes;?> value="<?php echo $uamUserGroup->getId(); ?>" name="<?php echo $groupsFormName; ?>[]" />
			<?php echo $uamUserGroup->getGroupName().$addition; ?>
		</label>
	</li>
	<?php
}
?>

</ul>