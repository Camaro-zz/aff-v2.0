<template>
<h1>Users</h1>
  <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users</h3>
            </div>
            <!-- /.box-header -->
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
    </section>
</template>

<script>
export default {
  ready () {
      this.showUsers()
  },
  data () {
      return {
          users: []
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
                  show_stack_success('删除成功！', response)
              }else{
                  show_stack_error(response.data.msg, response)
              }
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
