<?php if (!defined('CMS_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap">

  <Admintemplate file="Common/Nav"/>
  <div class="h_a">编辑计划任务</div>
  <form class="J_ajaxForm"  action="{:U('Cron/edit')}" method="post">
    <div class="table_full">
      <table width="100%">
        <col class="th" />
        <col width="400" />
        <col />
        <tr>
          <th>任务标题</th>
          <td><input type="text" class="input length_5 mr5" name="subject" value="{$subject}"></td>
          <td><div class="fun_tips"></div></td>
        </tr>
        <tr>
          <th>执行时间</th>
          <td><select id="J_time_select" name="loop_type" class="mr10">
          <volist name="loopType" id="vo">
              <option value="{$key}"  <if condition=" $key eq $loop_type ">selected</if>>{$vo}</option>
           </volist>
            </select>
            <span class="J_time_item" id="J_time_month"  <if condition=" $loop_type neq 'month' ">style="display:none;"</if>>
            <select class="select_2 mr10" name="month_day">
              <for start="1" end="31">
              <option value="{$i}"  <if condition=" $i eq $day ">selected</if>>{$i}日</option>
              </for>
              <option value="99" <if condition=" 99 eq $day ">selected</if>>最后一天</option>
            </select>
            <select class="select_2"  name="month_hour">
              <for start="0" end="23">
              <option value="{$i}"  <if condition=" $i eq $hour ">selected</if>>{$i}点</option>
              </for>
            </select>
            </span> <span class="J_time_item" id="J_time_week"   <if condition=" $loop_type neq 'week' ">style="display:none;"</if> >
            <select class="select_2 mr10" name="week_day">
              <option value="1" <if condition=" 1 eq $day ">selected</if>>周一</option>
              <option value="2" <if condition=" 2 eq $day ">selected</if>>周二</option>
              <option value="3" <if condition=" 3 eq $day ">selected</if>>周三</option>
              <option value="4" <if condition=" 4 eq $day ">selected</if>>周四</option>
              <option value="5" <if condition=" 5 eq $day ">selected</if>>周五</option>
              <option value="6" <if condition=" 6 eq $day ">selected</if>>周六</option>
              <option value="0" <if condition=" 0 eq $day ">selected</if>>周日</option>
            </select>
            <select class="select_2" name="week_hour">
              <for start="0" end="23">
              <option value="{$i}"  <if condition=" $i eq $hour ">selected</if>>{$i}点</option>
              </for>
            </select>
            </span> <span class="J_time_item" id="J_time_day"    <if condition=" $loop_type neq 'day' ">style="display:none;"</if> >
            <select class="select_2 mr10"  name="day_hour">
              <for start="0" end="23">
              <option value="{$i}"  <if condition=" $i eq $hour ">selected</if>>{$i}点</option>
              </for>
            </select>
            </span> <span class="J_time_item" id="J_time_hour"   <if condition=" $loop_type neq 'hour' ">style="display:none;"</if>>
            <select class="select_2" name="hour_minute">
              <option value="0" <if condition=" 0 eq $minute ">selected</if>>00分</option>
              <option value="10"  <if condition=" 10 eq $minute ">selected</if>>10分</option>
              <option value="20"  <if condition=" 20 eq $minute ">selected</if>>20分</option>
              <option value="30"  <if condition=" 30 eq $minute ">selected</if>>30分</option>
              <option value="40"  <if condition=" 40 eq $minute ">selected</if>>40分</option>
              <option value="50"  <if condition=" 50 eq $minute ">selected</if>>50分</option>
            </select>
            </span> <span class="J_time_item" id="J_time_now"   <if condition=" $loop_type neq 'now' ">style="display:none;"</if> >
            <?php
			if ($day) $time =  $day;
			if ($hour) $time =  $hour;
			if ($minute) $time =  $minute;
			if(empty($time)){
			    $time = 1;
            }
			?>
            <input type="number" class="input length_2 mr5" name="now_time" value="{$time}">
            <select class="select_2" name="now_type">
              <option value="minute" <if condition=" $minute ">selected</if> >分钟</option>
              <option value="hour"  <if condition=" $hour ">selected</if>>小时</option>
              <option value="day" <if condition=" $day ">selected</if>>天</option>
            </select>
            </span></td>
          <td><div class="fun_tips"></div></td>
        </tr>
        <tr>
          <th>开启计划</th>
          <td><ul class="switch_list cc">
              <li>
                <label>
                  <input type="radio" name="isopen" value="1" <if condition=" $isopen  ">checked</if>>
                  <span>开启</span></label>
              </li>
              <li>
                <label>
                  <input type="radio" name="isopen" value="0"  <if condition=" $isopen eq 0 ">checked</if>>
                  <span>关闭</span></label>
              </li>
            </ul></td>
          <td><div class="fun_tips"></div></td>
        </tr>
        <tr>
          <th>任务类型</th>
          <td><select id="J_type_select" name="type" class="mr10">
              <option value="0">普通计划任务</option>
            </select>
          </td>
            <td></td>
        </tr>

        <tr>
          <th>执行文件</th>
          <td><select class="select_6 mr5" name="cron_file">
              <volist name="fileList" id="vo">
              <option value="{$vo}" <if condition=" $cron_file eq  $vo">selected</if>>{$vo}</option>
              </volist>
            </select></td>
          <td></td>
        </tr>
      </table>
    </div>
    <div class="">
      <div class="btn_wrap_pd">
        <button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
        <input type="hidden" name="cron_id" value="{$cron_id}">
      </div>
    </div>
  </form>
  <!--结束--> 
  
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script> 
<script>
$(function(){
	$('#J_time_select').on('change', function(){
		$('#J_time_'+ $(this).val()).show().siblings('.J_time_item').hide();
	});
	$("#J_type_select").on('change', function(){
		if($(this).val() == "0"){
			$('.J_type_item').hide();
		}else{
			$('#type'+ $(this).val()).show().siblings('.J_type_item').hide();
		}
	});
});
</script>
</body>
</html>
