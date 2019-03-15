<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap" id="app" v-cloak>
    <div class="h_a">概览</div>
    <form class="J_ajaxForm">
        <div class="table_full">
            <table width="100%">
                <col class="th"/>
                <col width="400"/>
                <col/>
                <tr>
                    <th>计划任务启用状态</th>
                    <td>
                        <template v-if="cron_config.enable_cron == 1">
                            <span style="color: green">启用中 </span>
                            <button class="btn btn-danger" type="button" @click="toSetCronEnable(0)">停用</button>
                        </template>
                        <template v-else>
                            <span style="color: red">停用中 </span>
                            <button class="btn btn-success" type="button" @click="toSetCronEnable(1)">启用</button>
                        </template>
                        <p><span style="color: red;">*</span> 停用计划任务是平滑进行。需要等待该轮的任务调度执行完成后，再完全停止。</p>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th>密钥</th>
                    <td>
                        <input type="text" class="input length_5 mr5"  v-model="cron_config.secret_key">
                        <button class="btn btn-primary" type="button" @click="toSetSecretKey">更新</button>

                    </td>
                    <td>
                        <div class="fun_tips"></div>
                    </td>
                </tr>
                <tr>
                    <th>当前执行状态</th>
                    <td></td>
                    <td>
                        <div class="fun_tips"></div>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: right">正在执行数量</th>
                    <td>
                        <strong>{{cron_status.current_exec_amount}}</strong>

                    </td>
                    <td></td>
                </tr>

                <tr>
                    <th style="text-align: right">当前执行任务</th>
                    <td>
                        <template v-for="cron in cron_status.current_exec_cron">
                            <p><strong>{{cron.subject}}({{cron.cron_file}})</strong></p>
                        </template>
                        <template v-if="cron_status.current_exec_cron.length == 0">
                            <p><strong>暂无</strong></p>
                        </template>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="">
            <div class="btn_wrap_pd">
                <button class="btn btn_submit" type="button" @click="getStatus">获取最新状态</button>
            </div>
        </div>
    </form>
    <!--结束-->

</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                items: [],
                cron_config: {
                    enable_cron: 0,
                    secret_key: ''
                },
                cron_status: {
                    current_exec_amount: 0,
                    current_exec_cron: [],
                }
            },

            methods: {
                getStatus: function () {
                    var that = this;
                    var data = {};
                    $.ajax({
                        url: "{:U('Cron/Cron/getCronStatus')}",
                        data: data,
                        dataType: 'json',
                        type: 'get',
                        success: function (res) {
                            var data = res.data;
                            that.cron_config = data.cron_config;
                            that.cron_status = data.cron_status;
                        }
                    })
                },
                setCronEnable: function(value){
                    var that = this;
                    var data = {
                        enable: value
                    };
                    $.ajax({
                        url: "{:U('Cron/Cron/setCronEnable')}",
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        success: function (res) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                that.getStatus()
                            }, 1000)
                        }
                    })
                },
                toSetCronEnable: function(value){
                    var that = this;
                    layer.confirm('修改密钥将会影响计划任务运行，请在用户流量少的情况下进行操作。确认要操作？', {
                        btn: ['确认','取消'] //按钮
                    }, function(){
                        that.setCronEnable(value);
                    }, function(){

                    });
                },
                toSetSecretKey: function(){
                    var that = this;
                    layer.confirm('修改密钥将会影响计划任务运行，请在用户流量少的情况下进行操作。确认要操作？', {
                        btn: ['确认','取消'] //按钮
                    }, function(){
                        that.setSecretKey();
                    }, function(){

                    });
                },
                setSecretKey: function(){
                    var that = this;
                    var data = {
                        secret_key: that.cron_config.secret_key
                    };
                    $.ajax({
                        url: "{:U('Cron/Cron/setCronSecretKey')}",
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        success: function (res) {
                            layer.msg(res.msg);
                            setTimeout(function(){
                                that.getStatus()
                            }, 1000)
                        }
                    })
                },
            },
            mounted: function () {
                this.getStatus();
            }
        })
    })
</script>
</body>
</html>
