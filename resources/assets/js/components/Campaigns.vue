<template>
<section class="content">
  <h1>Campaigns</h1>
  <!--<button type="button" @click="createPost" class="btn btn-lg btn-primary btn-flat" style="margin-bottom: 15px;">
    Create Post
  </button>-->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">

            时间选项：
            <select name="date_type" v-model="date_type" v-on:change="fetchCampaigns()">
              <option value="0">所有时间</option>
              <option value="1">今天</option>
              <option value="2">昨天</option>
              <option value="3">本周</option>
              <option value="4">上周</option>
              <option value="5">本月</option>
              <option value="6">上月</option>
              <option value="7">本年</option>
              <option value="8">去年</option>
            </select>


            时区选项：
            <select name="timezone" v-model="timezone" v-on:change="fetchCampaigns()">
              <option value="12">东西12时区</option>
              <option value="11">西11时区</option>
              <option value="10">西10时区</option>
              <option value="9">西9时区</option>
              <option value="8">西8时区</option>
              <option value="7">西7时区</option>
              <option value="6">西6时区</option>
              <option value="5">西5时区</option>
              <option value="4">西4时区</option>
              <option value="3">西3时区</option>
              <option value="2">西2时区</option>
              <option value="1">西1时区</option>
              <option value="0">0时区</option>
              <option value="-1">东1时区</option>
              <option value="-2">东2时区</option>
              <option value="-3">东3时区</option>
              <option value="-4">东4时区</option>
              <option value="-5">东5时区</option>
              <option value="-6">东6时区</option>
              <option value="-7">东7时区</option>
              <option value="-8">东8时区</option>
              <option value="-9">东9时区</option>
              <option value="-10">东10时区</option>
              <option value="-11">东11时区</option>
            </select>
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tr>
              <th>ID</th>
              <th>标题</th>
              <th>状态</th>
              <th>Clicks(总点击量)</th>
              <th>LP Views(LP展示量)</th>
              <th>LP Click(LP点击量)</th>
              <th>LP CTR(LP点击率)</th>
              <th>Leads(转化)</th>
              <th>CVR(转化率)</th>
              <th>CPC</th>
              <th>EPC</th>
              <th>Rev.</th>
              <th>Spend</th>
              <th>P/L</th>
              <th>ROI</th>
              <th>操作</th>
            </tr>
            <tr v-for="camp in list">
              <td>{{camp.camp_id}}</td>
              <td class="col-md-3">{{camp.camp_name}}</td>
              <td class="col-md-1">
                  <b v-if="camp.camp_status == 0" class="label label-success">启用</b>
                  <b v-else class="label label-danger">禁用</b>
              </td>
              <td class="col-md-1">{{camp.clicks}}</td>
              <td class="col-md-1">{{camp.lpviews}}</td>
              <td class="col-md-1">{{camp.lpclicks}}</td>
              <td class="col-md-1">{{camp.ctr}}%</td>
              <td class="col-md-1">{{camp.leads}}</td>
              <td class="col-md-1">{{camp.cvr}}%</td>
              <td class="col-md-1">{{camp.cpc}}</td>
              <td class="col-md-1">{{camp.epc}}</td>
              <td class="col-md-1">${{camp.rev}}</td>
              <td class="col-md-1">${{camp.cost}}</td>
              <td class="col-md-1">
                <template v-if="camp.profit > 0">
                  <b style="color:green;">${{camp.profit}}</b>
                </template>
                <template v-else>
                  <b style="color:red;">${{-camp.profit}}</b>
                </template>
              </td>
              <td class="col-md-1">
                <template v-if="camp.roi > 0">
                  <b style="color:green;">{{camp.roi}}%</b>
                </template>
                <template v-else>
                  <b style="color:red;">{{camp.roi}}%</b>
                </template>
              </td>
              <td class="col-md-3">
                <div class="btn-group">
                  <a class="btn btn-info" v-link="{ name: 'campaigns', params: {hashid: camp.camp_id}}">详情</a>
                </div>
              </td>
              <td>
                <!--<b v-if="post.status == 'approved'" class="label label-success">{{post.status}}</b>
                <b v-if="post.status == 'postponed'" class="label label-info">{{post.status}}</b>
                <b v-if="post.status == 'pending'" class="label label-warning">{{post.status}}</b>
                <b v-if="post.status == 'rejected'" class="label label-danger">{{post.status}}</b>-->
              </td>
            </tr>
          </table>
          <div class="page-bar">
            <ul>
              <li><a v-on:click="goPage(0)">上一页</a></li>
              <li v-for="index in indexs"  v-bind:class="{ 'active': cur == index}">
                <a v-on:click="btnClick(index)">{{ index }}</a>
              </li>
              <li><a v-on:click="goPage(1)">下一页</a></li>
              <li><a>共<i>{{all}}</i>页</a></li>
            </ul>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
</section>
</template>

<style>
  ul,li{
    margin: 0px;
    padding: 0px;
  }
  li{
    list-style: none
  }
  .page-bar li:first-child>a {
    margin-left: 0px
  }
  .page-bar a{
    border: 1px solid #ddd;
    text-decoration: none;
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    cursor: pointer
  }
  .page-bar a:hover{
    background-color: #eee;
  }
  .page-bar .active a{
    color: #fff;
    cursor: default;
    background-color: #337ab7;
    border-color: #337ab7;
  }
  .page-bar i{
    font-style:normal;
    color: #d44950;
    margin: 0px 4px;
    font-size: 12px;
  }
</style>

<script>
import Multiselect from 'vue-multiselect/lib/vue-multiselect.js'
import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

export default {
  props: ['cur', 'all'],
  components: {
    Multiselect
  },
  ready () {
   this.fetchCampaigns()
  },
  data () {
    return {
      list: [],
      timezone: 12,
      date_type: 1,
      cur: 1,
    }
  },
  methods: {
    fetchCampaigns () {
      var date_type = this.date_type;
      var timezone  = this.timezone;
      var page = this.cur ? this.cur : 1;
      this.$http({url: '/camp/list.json?page='+page+'&date_type='+date_type+'&timezone='+timezone, method: 'GET'}).then(function (response) {
        this.$set('list', response.data.data)
        this.$set('all', response.data.count)
        this.$set('cur', response.data.page)
      })
    },
    btnClick: function(data){
      if(data != this.cur){
        this.cur = data
        this.$dispatch('btn-click',data)
        this.fetchCampaigns()
      }
    },
    goPage(type){
      if(type == 1){
        if(this.cur < this.all){
          this.cur++
        }
      }else{
        if(this.cur > 1){
          this.cur--
        }
      }
      this.fetchCampaigns()
    }
  },
  computed: {
    indexs: function(){
      var left = 1
      var right = this.all
      var ar = []
      if(this.all>= 11){
        if(this.cur > 5 && this.cur < this.all-4){
          left = this.cur - 5
          right = this.cur + 4
        }else{
          if(this.cur<=5){
            left = 1
            right = 10
          }else{
            right = this.all
            left = this.all -9
          }
        }
      }
      while (left <= right){
        ar.push(left)
        left ++
      }
      return ar
    }
  },
}
</script>
