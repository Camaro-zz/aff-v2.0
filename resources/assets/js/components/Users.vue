<template>
<h1>Users</h1>
  <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                    新增用户
                </button>
            </div>
            <!-- /.box-header -->
            <div class="btn-group">

            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>头像</th>
                  <th>姓名</th>
                  <th>用户名</th>
                  <th>邮箱</th>
                  <th></th>
                </tr>
                <tr v-for="user in users">
                  <td><img class="profile-user-img img-responsive img-circle"
                           :src="user.avatar ? user.avatar : '/img/avatar.jpg'" alt="User profile picture"></td>
                  <td>{{user.name}}</td>
                  <td>{{user.username}}</td>
                  <td><span class="label label-success">{{user.email}}</span></td>
                  <td>
                      <div class="btn-group">
                          <a class="btn btn-info" v-link="{ name: 'camp_user', params: {hashid: user.id}}">camp授权设置</a>
                      </div>
                      <div class="btn-group">
                          <a class="btn btn-info" v-on:click="editUser(user.id)">修改</a>
                      </div>
                      <div class="btn-group">
                          <a class="btn btn-warning" v-on:click="delUser(user.id)">删除</a>
                      </div>
                  </td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel">{{modal_title}}</h4>
                  </div>
                  <div class="modal-body">
                      <form class="bs-example bs-example-form" role="form" id="add_user_form">
                          <input type="hidden" v-model="user_info.id">
                          <div class="input-group">
                              <span class="input-group-addon">邮  箱</span>
                              <input type="text" name="email" class="form-control" id="email" placeholder="邮  箱" v-model="user_info.email">
                          </div>
                          <br>
                          <div class="input-group">
                              <span class="input-group-addon">姓  名</span>
                              <input type="text" name="name" class="form-control" id="name" placeholder="姓  名" v-model="user_info.name">
                          </div>
                          <br>
                          <div class="input-group">
                              <span class="input-group-addon">用户名</span>
                              <input type="text" name="username" class="form-control" id="username" placeholder="用户名" v-model="user_info.username">
                          </div>
                          <br>
                          <div class="input-group">
                              <span class="input-group-addon">密  码</span>
                              <input type="text" name="password" class="form-control" id="password" placeholder="密  码" v-model="user_info.password">
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                      <button type="button" class="btn btn-primary" v-on:click="saveUser()">保存</button>
                  </div>
              </div>
          </div>
      </div>
    </section>
</template>

<script>
import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'
export default {
  ready () {
      this.showUsers()
  },
  data () {
      return {
          users: [],
          user_info: {
              id: '',
              name:'',
              username:'',
              password:'',
              email: ''
          },
          modal_title: '新增用户'
      }
  },
  methods:{
      showUsers(){
          this.$http({url: '/camp/users.json', method: 'GET'}).then(function (response) {
              this.$set('users', response.data.data)
          })
      },
      delUser(id){
          this.$http({url: '/admin/del_user/'+id+'.json', method: 'post'}).then(function (response) {
              if(response.data.status == true){
                  show_stack_success(response.data.msg, response);
                  this.showUsers();
              }else{
                  show_stack_error(response.data.msg, response);
              }
          })
      },
      saveUser(){
          var data = this.user_info;
          this.$http.post('/admin/save_user.json', data).then((response) => {
              if(response.data.status == false){
                show_stack_error(response.data.msg, response)
              }else{
                show_stack_success('保存成功！', response)
                this.showUsers();
                this.$set('user_info', '')
                $("#myModal").modal('hide')
              }
          }, function (response){
              show_stack_error('保存失败！', response)
          });
      },
      editUser(uid){
          this.$set('modal_title', '修改用户');
          this.$http({url: '/user/user_info/'+uid+'.json', method: 'GET'}).then(function (response) {
              $('#myModal').modal('show');
              this.$set('user_info', response.data.data)
          })
      }
  }
}
</script>

<style>
  body {
    font-family: Helvetica, sans-serif;
  }
</style>
