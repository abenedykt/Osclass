<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminStats extends AdminSecBaseModel
    {
        //specific for this class

        function __construct() {
            parent::__construct() ;

            //specific things for this class
        }

        //Business Layer...
        function doModel() {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {
                case 'reports':        // manage stats view
                                        if(Params::getParam('type_stat')=='week') {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d',  mktime (0,0,0, date("m"), date("d") -70, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -70 +1 , date("Y"));
                                        } else if(Params::getParam('type_stat')=='month') {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d',  mktime (0,0,0, date("m")-10, date("d"), date("Y"))));
                                            $first = mktime (0,0,0, date("m")-10, date("d") +1, date("Y"));
                                        } else {
                                            $stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d',  mktime (0,0,0, date("m"), date("d") -10, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -10 +1, date("Y"));
                                        }
                                        $reports = array();
                                        $last = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
                                        $days = round(($last - $first) / (60 * 60 * 24));
                                        for($k = $first;$k<=$last;$k+=(24*3600)) {
                                            $reports[date('Y-m-d', $k)]['views'] = 0;
                                            $reports[date('Y-m-d', $k)]['spam'] = 0;
                                            $reports[date('Y-m-d', $k)]['repeated'] = 0;
                                            $reports[date('Y-m-d', $k)]['bad_classified'] = 0;
                                            $reports[date('Y-m-d', $k)]['offensive'] = 0;
                                            $reports[date('Y-m-d', $k)]['expired'] = 0;
                                        }
                                        $max = array();
                                        $max['views'] = 0;
                                        $max['other'] = 0;
                                        foreach($stats_reports as $report) {
                                            $reports[$report['d_date']]['views'] = $report['views'];
                                            $reports[$report['d_date']]['spam'] = $report['spam'];
                                            $reports[$report['d_date']]['repeated'] = $report['repeated'];
                                            $reports[$report['d_date']]['bad_classified'] = $report['bad_classified'];
                                            $reports[$report['d_date']]['offensive'] = $report['offensive'];
                                            $reports[$report['d_date']]['expired'] = $report['expired'];
                                            if($report['views']>$max['views']) {
                                                $max['views'] = $report['views'];
                                            }
                                            if($report['spam']>$max['other']) {
                                                $max['other'] = $report['spam'];
                                            }
                                            if($report['repeated']>$max['other']) {
                                                $max['other'] = $report['repeated'];
                                            }
                                            if($report['bad_classified']>$max['other']) {
                                                $max['other'] = $report['bad_classified'];
                                            }
                                            if($report['offensive']>$max['other']) {
                                                $max['other'] = $report['offensive'];
                                            }
                                            if($report['expired']>$max['other']) {
                                                $max['other'] = $report['expired'];
                                            }
                                        }
                                        $this->_exportVariableToView("reports", $reports);
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/reports.php");
                break;
                
                
                case 'comments':        // manage stats view
                                        if(Params::getParam('type_stat')=='week') {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -70, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -70 +1, date("Y"));
                                        } else if(Params::getParam('type_stat')=='month') {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m")-10, date("d"), date("Y"))));
                                            $first = mktime (0,0,0, date("m")-10, date("d") +1, date("Y"));
                                        } else {
                                            $stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -10, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -10 +1, date("Y"));
                                        }
                                        $comments = array();
                                        $last = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
                                        $days = round(($last - $first) / (60 * 60 * 24));
                                        for($k = $first;$k<=$last;$k+=(24*3600)) {
                                            $comments[date('Y-m-d', $k)] = 0;
                                        }
                                        $max = 0;
                                        foreach($stats_comments as $comment) {
                                            $comments[$comment['d_date']] = $comment['num'];
                                            if($comment['num']>$max) {
                                                $max = $comment['num'];
                                            }
                                        }
                                        $this->_exportVariableToView("comments", $comments);
                                        $this->_exportVariableToView("latest_comments", Stats::newInstance()->latest_comments());
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/comments.php");
                break;
                
                
                case 'items':           // manage stats view
                                        if(Params::getParam('type_stat')=='week') {
                                            $stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -70, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -70 +1, date("Y"));
                                        } else if(Params::getParam('type_stat')=='month') {
                                            $stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m")-10, date("d"), date("Y"))));
                                            $first = mktime (0,0,0, date("m")-10, date("d") +1, date("Y"));
                                        } else {
                                            $stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -10, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -10 +1, date("Y"));
                                        }
                                        $items = array();
                                        $last = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
                                        $days = round(($last - $first) / (60 * 60 * 24));
                                        for($k = $first;$k<=$last;$k+=(24*3600)) {
                                            $items[date('Y-m-d', $k)] = 0;
                                        }
                                        $max = 0;
                                        foreach($stats_items as $item) {
                                            $items[$item['d_date']] = $item['num'];
                                            if($item['num']>$max) {
                                                $max = $item['num'];
                                            }
                                        }
                                        
                                        $this->_exportVariableToView("items", $items);
                                        $this->_exportVariableToView("latest_items", Stats::newInstance()->latest_items());
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/items.php");
                break;
                
                
                case 'users':           // manage stats view
                                        if(Params::getParam('type_stat')=='week') {
                                            $stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -70, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -70 +1, date("Y"));
                                        } else if(Params::getParam('type_stat')=='month') {
                                            $stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m")-10, date("d"), date("Y"))));
                                            $first = mktime (0,0,0, date("m")-10, date("d") +1, date("Y"));
                                        } else {
                                            $stats_users = Stats::newInstance()->new_users_count(date('Y-m-d H:i:s',  mktime (0,0,0, date("m"), date("d") -10, date("Y"))));
                                            $first = mktime (0,0,0, date("m"), date("d") -10 +1, date("Y"));
                                        }
                                        $users = array();
                                        $last = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
                                        $days = round(($last - $first) / (60 * 60 * 24));
                                        for($k = $first;$k<=$last;$k+=(24*3600)) {
                                            $users[date('Y-m-d', $k)] = 0;
                                        }
                                        $max = 0;
                                        foreach($stats_users as $user) {
                                            $users[$user['d_date']] = $user['num'];
                                            if($user['num']>$max) {
                                                $max = $user['num'];
                                            }
                                        }
                                        $item = Stats::newInstance()->items_by_user();
                                        $this->_exportVariableToView("users_by_country", Stats::newInstance()->users_by_country());
                                        $this->_exportVariableToView("users_by_region", Stats::newInstance()->users_by_region());
                                        $this->_exportVariableToView("item", $item['avg']);
                                        $this->_exportVariableToView("latest_users", Stats::newInstance()->latest_users());
                                        $this->_exportVariableToView("users", $users);
                                        $this->_exportVariableToView("max", $max);
                                        $this->doView("stats/users.php");
                break;
                
                
                default:                // manage stats view
                                        $users = array();
                                        $this->_exportVariableToView("users", $users);
                                        $this->doView("stats/users.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
        }
    }

?>