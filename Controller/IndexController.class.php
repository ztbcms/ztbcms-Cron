<?php

// +----------------------------------------------------------------------
// | 计划任务
// +----------------------------------------------------------------------

namespace Cron\Controller;


use Cron\Model\CronConfigModel;
use Cron\Model\CronLogModel;
use Cron\Service\CronService;

class IndexController extends AuthCronController {

    private $error_count = 0;
    private $cron_count = 0;
    private $cron_config = null;

	//初始化
	protected function _initialize() {
		parent::_initialize();
		//单个任务最大执行时间
		$CRON_MAX_TIME = C('CRON_MAX_TIME');
		if (empty($CRON_MAX_TIME)) {
			C('CRON_MAX_TIME', 3000);
		}

        $this->cron_config = CronService::getConfig()['data'];
	}

	//执行计划任务
	public function index() {
	    $start_at = $end_at= time();

        //判断计划任务是否关闭
	    if($this->cron_config[CronConfigModel::KEY_ENABLE_CRON] != CronConfigModel::ENABLE_YES){
            $this->ajaxReturn($this->createReturn(false, ['used_time' => 0], 'Cron status: stop'));
            return;
        }
		// 锁定自动执行
		$lockfile = RUNTIME_PATH . 'cron.lock';
		if (is_writable($lockfile) && filemtime($lockfile) > $_SERVER['REQUEST_TIME'] - C('CRON_MAX_TIME')) {
			//return;
		} else {
			//设置指定文件的访问和修改时间
			touch($lockfile);
		}
		set_time_limit(0);
		ignore_user_abort(true);

		//日志信息
		$log_data = [
		    'start_time' => time(),
            'end_time' => 0,
            'use_time' => 0,
            'error_count' => 0,
            'cron_count' => 0,
        ];

		//执行计划任务
		$this->runCron();

		//记录执行日志
		$log_data['end_time'] = time();
        $log_data['use_time'] = $log_data['end_time'] - $log_data['start_time'];
        $log_data['error_count'] = $this->error_count;
        $log_data['cron_count'] = $this->cron_count;

        //记录执行日志
        D('Cron/SchedulingLog')->add($log_data);

		// 解除锁定
		unlink($lockfile);

        $end_at = time();
        $used_time = $end_at-$start_at;
        $this->ajaxReturn($this->createReturn(true, ['used_time' => $used_time], 'Cron status: finish'));
        return;
	}

	/**
	 * 递归执行计划任务
	 */
	private function runCron() {
		$_time = time();
		$cron = D("Cron/Cron")->where(array("isopen" => array("EGT", 1)))->order(array("next_time" => "ASC"))->find();
		//检测是否还有需要执行的任务
		if (!$cron || $cron['next_time'] > $_time) {
			return false;
		}

		//记录cron数量
        $this->cron_count++;

		list($day, $hour, $minute) = explode('-', $cron['loop_daytime']);
		//获取下一次执行时间
		$nexttime = D("Cron/Cron")->getNextTime($cron['loop_type'], $day, $hour, $minute);
		//更新计划任务的下次执行时间
		D("Cron/Cron")->where(array("cron_id" => $cron['cron_id']))->save(array(
			"modified_time" => $_time,
			"next_time" => $nexttime,
		));
		if (!$this->_runAction($cron['cron_file'], $cron['cron_id'])) {
			return false;
		}
		//自身调用
		$this->runCron();
		return true;
	}

	//运行计划
	private function _runAction($filename = '', $cronId = 0) {
		//载入文件
        $class = $filename;
        $start_time = time();
        $end_time = 0;
        $result = CronLogModel::RESULT_PROCESSING;

        $cron_log_id =  D('Cron/CronLog')->add([
            'start_time' => $start_time,
            'end_time' => $end_time,
            'result' => $result,
            'cron_id' => $cronId,
            'use_time' => 0
        ]);
        try {
            $cron = new $class();
            $start_time = time();
            $cron->run($cronId);

            //处理完成
            $end_time = time();
            D('Cron/CronLog')->where(['id' => $cron_log_id])->save([
                'result' => CronLogModel::RESULT_SUCCESS,
                'end_time' => $end_time,
                'use_time' => $end_time - $start_time
            ]);

        } catch (\Exception $exception) {
            //异常
            $this->error_count++;

            $end_time = time();
            D('Cron/CronLog')->where(['id' => $cron_log_id])->save([
                'result' => CronLogModel::RESULT_FAIL,
                'end_time' => $end_time,
                'use_time' => $end_time - $start_time,
                'result_msg' => $exception->getMessage()
            ]);
        } catch (\Error $error){
            //错误
            $this->error_count++;

            $errorStr =  $error->getMessage().' '.$error->getFile()." 第 " . $error->getLine() ." 行.\n";
            $errorStr .= $error->getTraceAsString();

            $end_time = time();
            D('Cron/CronLog')->where(['id' => $cron_log_id])->save([
                'result' => CronLogModel::RESULT_FAIL,
                'end_time' => $end_time,
                'use_time' => $end_time - $start_time,
                'result_msg' => $errorStr
            ]);
        }

		return true;
	}

    //立即运行计划任务,只允许管理员调用
    public function runAction($filename = '', $cronId = 0){
		set_time_limit(0);
        ignore_user_abort(true);
        if(defined("IN_ADMIN") && IN_ADMIN){
            return $this->_runAction($filename, $cronId);
        }else{
            echo '无权限访问';
            exit();
        }

    }

}
