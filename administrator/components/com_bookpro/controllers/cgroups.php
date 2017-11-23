<?php


defined('_JEXEC') or die;

class BookproControllerCgroups extends JControllerAdmin {


    public function getModel($name = 'cgroup', $prefix = 'BookproModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

}