<?php
/**
 * GNU social plugin for "tag clouds" in the UI
 *
 * @category  UI
 * @package   GNUsocial
 * @author    Mikael Nordfeldth <mmn@hethane.se>
 * @copyright 2016 Free Software Foundation, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
 * @link      http://gnu.io/social/
 */

if (!defined('GNUSOCIAL')) { exit(1); }

class TagCloudPlugin extends Plugin {

    public function onRouterInitialized(URLMapper $m)
    {
        $m->connect('tags/', array('action' => 'publictagcloud'));
        $m->connect('tag/', array('action' => 'publictagcloud'));
        $m->connect('tags', array('action' => 'publictagcloud'));
        $m->connect('tag', array('action' => 'publictagcloud'));
    }

    public function onEndPublicGroupNav(Menu $menu)
    {
        // TRANS: Menu item in search group navigation panel.
        $menu->out->menuItem(common_local_url('publictagcloud'), _m('MENU','Recent tags'),
                             // TRANS: Menu item title in search group navigation panel.
                             _('Recent tags'), $menu->actionName === 'publictagcloud', 'nav_recent-tags');
    }

    public function onEndShowSections(Action $action)
    {
        $cloud = null;

        switch (true) {
        case $action instanceof AllAction:
            $cloud = new InboxTagCloudSection($action, $action->getTarget());
            break;
        case $action instanceof AttachmentAction:
            $cloud = new AttachmentTagCloudSection($action);
            break;
        case $action instanceof PublicAction:
            $cloud = new PublicTagCloudSection($action);
            break;
        case $action instanceof ShowstreamAction:
            $cloud = new PersonalTagCloudSection($action, $action->getTarget());
            break;
        case $action instanceof GroupAction:
            $cloud = new GroupTagCloudSection($action, $action->getGroup());
        }

        if (!is_null($cloud)) {
            $cloud->show();
        }
    }

    public function onPluginVersion(array &$versions)
    {
        $versions[] = array('name' => 'TagCloud',
                            'version' => GNUSOCIAL_VERSION,
                            'author' => 'Mikael Nordfeldth',
                            'homepage' => 'https://gnu.io/social',
                            'description' =>
                            // TRANS: Plugin description.
                            _m('Adds tag clouds to stream pages'));
        return true;
    }
}
