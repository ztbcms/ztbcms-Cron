<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Controller;

use Common\Controller\Base;

/**
 * 私钥校验
 */
class AuthCronController extends Base {

    protected function _initialize() {
        parent::_initialize();

        $cron_secret_key = I('get.cron_secret_key', '');
        if($cron_secret_key != C('CRON_SECRET_KEY')){
            echo '私钥不匹配';
            exit();
        }
    }


}