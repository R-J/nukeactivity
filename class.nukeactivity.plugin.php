<?php

class NukeActivityPlugin extends Gdn_Plugin {
    /**
     * Disable Activities on profile page on setup.
     *
     * @return void.
     */
    public function setup() {
        saveToConfig('Garden.Profile.ShowActivities', false);

        $roles = RoleModel::roles();
        $permissionModel = new PermissionModel();
        foreach ($roles as $roleID => $role) {
            $permissionModel->save(
                [
                    'RoleID' => $roleID,
                    'Garden.Activity.View' => 0
                ]
            );
        }
    }

    /**
     * Re-enable Activities on profile page on plugin deactivation.
     *
     * @return void.
     */
    public function onDisable() {
        saveToConfig('Garden.Profile.ShowActivities', true);

        $roles = RoleModel::roles();
        $permissionModel = new PermissionModel();
        foreach ($roles as $roleID => $role) {
            $permissionModel->save(
                [
                    'RoleID' => $roleID,
                    'Garden.Activity.View' => 1
                ]
            );
        }
    }

    /**
     * Make link to Activities disappear.
     *
     * @param Smarty $sender Instance of the calling class.
     *
     * @return void.
     */
    public function gdn_smarty_init_handler($sender) {
return;
        $sender->unregister_function('activity_link');
        $sender->register_function('activity_link', 'empty_activity_link');
    }

    /**
     * Show 404 if user is linked to Activity page.
     *
     * @param ActivityController $sender Instance of the calling class.
     * @param mixed $args Event arguments.
     *
     * @return void.
     */
    public function activityController_render_before($sender, $args) {
return;
        if ($sender->Request->getMethod() != 'GET') {
            return;
        }
        $redirectTarget = c('NukeActivity.RedirectTarget', 'Default404');
        $route = Gdn::Router()->getRoute($redirectTarget);
        if (isset($route['FinalDestination'])) {
            redirectTo($route['FinalDestination']);
        } else {
            redirectTo($route['Destination']);
        }
    }

    public function base_beforeDiscussionFilters_handler($sender) {
        echo '<style></style>';
    }
}

/**
 * Replaces the activity_link Smarty function. Returns an empty string.
 *
 * @param mixed $params Template parameters.
 * @param Smarty $smarty Instance of the Smarty class.
 *
 * @return string Empty string to prevent showing a link.
 */
/*
function empty_activity_link($params, &$smarty) {
      return '';
}
*/
