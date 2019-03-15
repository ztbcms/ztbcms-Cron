<?php

// +----------------------------------------------------------------------
// | 计划任务
// +----------------------------------------------------------------------

namespace Cron\Controller;

use Common\Controller\AdminBase;
use Common\Model\Model;
use Cron\Model\CronConfigModel;
use Cron\Model\CronLogModel;
use Cron\Model\CronModel;
use Cron\Service\CronService;

class CronController extends AdminBase {

    /**
     * @var CronModel
     */
    private $db;

    //初始化
    protected function _initialize() {
        parent::_initialize();
        $this->db = D("Cron/Cron");
    }

    public function index() {
        $count = $this->db->count();
        $page = $this->page($count, 20);
        $data = $this->db->limit($page->firstRow . ',' . $page->listRows)->order(array("cron_id" => "DESC"))->select();
        //created_time 上次执行时间
        //next_time 下次执行时间
        foreach ($data AS $key => &$cron) {
            $cron['type'] = $this->db->_getLoopType($cron['loop_type']);
            list($day, $hour, $minute) = explode('-', $cron['loop_daytime']);
            if ($cron['loop_type'] == 'week') {
                $cron['type'] .= '星期' . $this->db->_capitalWeek($day);
            } elseif ($day == 99) {
                $cron['type'] .= '最后一天';
            } else {
                $cron['type'] .= $day ? $day . '日' : '';
            }
            if ($cron['loop_type'] == 'week' || $cron['loop_type'] == 'month') {
                $cron['type'] .= $hour . '时';
            } else {
                $cron['type'] .= $hour ? $hour . '时' : '';
            }

            $cron['type'] .= $minute ? $minute . '分' : '00分';
        }

        $this->assign("data", $data);
        $this->assign("Page", $page->show());
        $this->display();
    }

    //添加计划任务
    public function add() {
        if (IS_POST) {
            if ($this->db->CronAdd($_POST)) {
                $this->success("计划任务添加成功！", U("Cron/index"));
            } else {
                $this->error($this->db->getError());
            }
        } else {
            $this->assign("fileList", $this->db->_getCronFileList());
            $this->display();
        }
    }

    //编辑
    public function edit() {
        if (IS_POST) {
            if ($this->db->CronEdit($_POST)) {
                $this->success("修改成功！", U("Cron/index"));
            } else {
                $this->error($this->db->getError());
            }
        } else {
            $cron_id = I('get.cron_id', 0, 'intval');
            $info = $this->db->where(array("cron_id" => $cron_id))->find();
            if (!$info) {
                $this->error("该计划任务不存在！");
            }
            list($info['day'], $info['hour'], $info['minute']) = explode('-', $info['loop_daytime']);

            $this->assign($info);
            $this->assign("loopType", $this->db->_getLoopType());
            $this->assign("fileList", $this->db->_getCronFileList());
            $this->display();
        }
    }

    //删除
    public function delete() {
        $cron_id = I('get.cron_id', 0, 'intval');
        $info = $this->db->where(array("cron_id" => $cron_id))->delete();
        if ($info !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //立即执行计划任务
    public function runAction() {
        $IndexController = new \Cron\Controller\IndexController();
        $cron_id = I('get.cron_id');
        $cron_file = I('get.cron_file');

        $res = $IndexController->runAction($cron_file, $cron_id);
    }

    /**
     * 计划任务日志列表页
     */
    function logs() {
        $cron_list = D('Cron/Cron')->select();
        $this->assign('cron_list', $cron_list);

        $this->display();
    }

    /**
     * 获取计划任务列表操作
     */
    function getCronLogs() {
        $cron_id = I('cron_id');
        $start_date = I('start_date');
        $end_date = I('end_date');
        $result = I('result');
        $use_time = I('use_time', 0);
        $page = I('page', 1);
        $limit = I('limit', 20);

        $where = array();
        if (!empty($cron_id)) {
            $where['cron_id'] = array('EQ', $cron_id);
        }
        if (!empty($start_date)) {
            $start_date = strtotime($start_date);
            $where['start_time'] = array('EGT', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = strtotime($end_date) + 24 * 60 * 60 - 1;
            $where['end_time'] = array('ELT', $end_date);
        }
        if (!empty($result)) {
            $where['result'] = array('EQ', $result);
        }
        if (!empty($use_time)) {
            $where['use_time'] = array('EGT', $use_time);
        }

        $count = D('Cron/CronLog')->where($where)->count();
        $total_page = ceil($count / $limit);
        $Logs = D('Cron/CronLog')->where($where)->page($page)->limit($limit)->order(array("id" => "desc"))->relation(true)->select();
        $data = [
            'items' => $Logs,
            'page' => $page,
            'limit' => $limit,
            'total_page' => $total_page,
        ];
        $this->ajaxReturn(self::createReturn(true, $data));
    }

    /**
     * 调度执行日志列表页
     */
    function scheduling_logs() {
        $this->display();
    }

    /**
     * 获取调度执行日志列表操作
     */
    function getSchedulingLogs() {
        $start_date = I('start_date');
        $end_date = I('end_date');
        $page = I('page', 1);
        $limit = I('limit', 20);
        $use_time = I('use_time', 0);

        $where = array();
        if (!empty($cron_id)) {
            $where['cron_id'] = array('EQ', $cron_id);
        }
        if (!empty($start_date)) {
            $start_date = strtotime($start_date);
            $where['start_time'] = array('EGT', $start_date);
        }
        if (!empty($end_date)) {
            $end_date = strtotime($end_date) + 24 * 60 * 60 - 1;
            $where['end_time'] = array('ELT', $end_date);
        }
        if (!empty($use_time)) {
            $where['use_time'] = array('EGT', $use_time);
        }

        $count = D('Cron/SchedulingLog')->where($where)->count();
        $total_page = ceil($count / $limit);
        $Logs = D('Cron/SchedulingLog')->where($where)->page($page)->limit($limit)->order(array("id" => "desc"))->select();
        $data = [
            'items' => $Logs,
            'page' => $page,
            'limit' => $limit,
            'total_page' => $total_page,
        ];
        $this->ajaxReturn(self::createReturn(true, $data));
    }

    function dashboard(){
        $this->display();
    }

    /**
     * 获取计划任务执行状态
     * @return array
     */
    function _getCronExecuteStatus(){
        $cron_execute_status = [
            'current_exec_amount' => 0, //正在执行任务数量
            'current_exec_cron' => [],//正在执行任务列表
        ];

        //正在执行任务数量
        $cronlog_list = D('Cron/CronLog')->where(['result' => CronLogModel::RESULT_PROCESSING])->select();
        $cron_execute_status['current_exec_amount'] = count($cronlog_list);

        //，正在执行任务列表
        $exec_cron_list = [];
        $exec_cron_map = [];
        foreach ($cronlog_list as $i => $log) {
            $cron = D('Cron/Cron')->where(['crod_id' => $log['cron_id']])->find();
            if (empty($exec_cron_map[$cron['cron_id']])) {
                $exec_cron_list [] = [
                    'cron_id' => $cron['cron_id'],
                    'subject' => $cron['subject'],
                    'cron_file' => $cron['cron_file'],
                ];
            }
        }
        $cron_execute_status['current_exec_cron'] = $exec_cron_list;

        return $cron_execute_status;
    }

    function getCronEntryUrl(){
        $cron_config = CronService::getConfig()['data'];
        return urlDomain(get_url()).'/Cron/Index/index/cron_secret_key/'.$cron_config[CronConfigModel::KEY_ENABLE_SECRET_KEY];
    }

    /**
     * 获取计划任务状态
     */
    function getCronStatus(){
        $cron_config = CronService::getConfig()['data'];

        $cron_status = $this->_getCronExecuteStatus();
        $this->ajaxReturn($this->createReturn(true, [
            'cron_config' => $cron_config,
            'cron_status' => $cron_status,
            'cron_entry_url' => $this->getCronEntryUrl(),
        ]));
    }

    /**
     * 设置计划任务开关
     */
    function setCronEnable(){
        $enable = I('enable');
        $this->ajaxReturn(CronService::setConfig(CronConfigModel::KEY_ENABLE_CRON, $enable));
    }

    /**
     * 设置密钥
     */
    function setCronSecretKey(){
        $secret_key = I('secret_key');
        $this->ajaxReturn(CronService::setConfig(CronConfigModel::KEY_ENABLE_SECRET_KEY, $secret_key));
    }
}
