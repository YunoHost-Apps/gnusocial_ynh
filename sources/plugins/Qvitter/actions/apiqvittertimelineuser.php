<?php
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
   ·                                                                            ·
   ·  Show timelines for both local and remote users                            ·
   ·                                                                            ·
   - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
  ·                                                                             ·
  ·                                                                             ·
  ·                             Q V I T T E R                                   ·
  ·                                                                             ·
  ·                      https://git.gnu.io/h2p/Qvitter                         ·
  ·                                                                             ·
  ·                                                                             ·
  ·                                 <o)                                         ·
  ·                                  /_////                                     ·
  ·                                 (____/                                      ·
  ·                                          (o<                                ·
  ·                                   o> \\\\_\                                 ·
  ·                                 \\)   \____)                                ·
  ·                                                                             ·
  ·                                                                             ·
  ·                                                                             ·
  ·  Qvitter is free  software:  you can  redistribute it  and / or  modify it  ·
  ·  under the  terms of the GNU Affero General Public License as published by  ·
  ·  the Free Software Foundation,  either version three of the License or (at  ·
  ·  your option) any later version.                                            ·
  ·                                                                             ·
  ·  Qvitter is distributed  in hope that  it will be  useful but  WITHOUT ANY  ·
  ·  WARRANTY;  without even the implied warranty of MERCHANTABILTY or FITNESS  ·
  ·  FOR A PARTICULAR PURPOSE.  See the  GNU Affero General Public License for  ·
  ·  more details.                                                              ·
  ·                                                                             ·
  ·  You should have received a copy of the  GNU Affero General Public License  ·
  ·  along with Qvitter. If not, see <http://www.gnu.org/licenses/>.            ·
  ·                                                                             ·
  ·  Contact h@nnesmannerhe.im if you have any questions.                       ·
  ·                                                                             ·
  · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · · */

if (!defined('GNUSOCIAL')) { exit(1); }

class ApiQvitterTimelineUserAction extends ApiBareAuthAction
{
    var $notices = null;

    var $next_id = null;

    /**
     * Take arguments for running
     *
     * @param array $args $_REQUEST args
     *
     * @return boolean success flag
     */
    protected function prepare(array $args=array())
    {
        parent::prepare($args);

        $this->target = $this->getTargetProfile($this->arg('id'));

        if (!($this->target instanceof Profile)) {
            // TRANS: Client error displayed requesting most recent notices for a non-existing user.
            $this->clientError(_('No such user.'), 404);
        }

        // if (!$this->target->isLocal()) {
        //     $this->serverError(_('Remote user timelines are not available here yet.'), 501);
        // }

        $this->notices = $this->getNotices();

        return true;
    }

    /**
     * Handle the request
     *
     * Just show the notices
     *
     * @return void
     */
    protected function handle()
    {
        parent::handle();

        $this->showTimeline();
    }

    /**
     * Show the timeline of notices
     *
     * @return void
     */
    function showTimeline()
    {
        $this->showJsonTimeline($this->notices);
    }

    /**
     * Get notices
     *
     * @return array notices
     */
    function getNotices()
    {
        $notices = array();

        $notice = $this->target->getNotices(($this->page-1) * $this->count,
                                          $this->count + 1,
                                          $this->since_id,
                                          $this->max_id,
                                          $this->scoped);

        while ($notice->fetch()) {
            if (count($notices) < $this->count) {
                $notices[] = clone($notice);
            } else {
                $this->next_id = $notice->id;
                break;
            }
        }

        return $notices;
    }

    /**
     * We expose AtomPub here, so non-GET/HEAD reqs must be read/write.
     *
     * @param array $args other arguments
     *
     * @return boolean true
     */

    function isReadOnly($args)
    {
        return true;
    }

    /**
     * When was this feed last modified?
     *
     * @return string datestamp of the latest notice in the stream
     */
    function lastModified()
    {
        if (!empty($this->notices) && (count($this->notices) > 0)) {
            return strtotime($this->notices[0]->created);
        }

        return null;
    }

    /**
     * An entity tag for this stream
     *
     * Returns an Etag based on the action name, language, user ID, and
     * timestamps of the first and last notice in the timeline
     *
     * @return string etag
     */
    function etag()
    {
        if (!empty($this->notices) && (count($this->notices) > 0)) {
            $last = count($this->notices) - 1;

            return '"' . implode(
                                 ':',
                                 array($this->arg('action'),
                                       common_user_cache_hash($this->scoped),
                                       common_language(),
                                       $this->target->getID(),
                                       strtotime($this->notices[0]->created),
                                       strtotime($this->notices[$last]->created))
                                 )
              . '"';
        }

        return null;
    }

}
