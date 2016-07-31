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
          <h3 class="box-title">All Campaigns</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tr>
              <th>ID</th>
              <th>标题</th>
              <th>状态</th>
              <th>总点击量</th>
              <th>总转化量</th>
              <th>LP展示量</th>
              <th>LP点击量</th>
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
              <td class="col-md-1">{{camp.leads}}</td>
              <td class="col-md-1">{{camp.lpviews}}</td>
              <td class="col-md-1">{{camp.lpclicks}}</td>
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
   this.fetchCampaigns(1)
  },
  data () {
    return {
      list: [],
    }
  },
  methods: {
    fetchCampaigns (page) {
      this.$http({url: '/camp/list.json?page='+page, method: 'GET'}).then(function (response) {
        this.$set('list', response.data.data)
        this.$set('all', response.data.count)
        this.$set('cur', response.data.page)
      })
    },
    btnClick: function(data){
      if(data != this.cur){
        this.cur = data
        this.$dispatch('btn-click',data)
        this.fetchCampaigns(data)
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
      this.fetchCampaigns(this.cur)
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
