<template>
<!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">个人设置</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->

      <form role="form">
        <div class="box-body">
          <img class="profile-user-img img-responsive img-circle"
               :src="user.avatar" alt="User profile picture">
          <div class="form-group">
            <label for="Email">邮箱:</label>
            <input v-model="user.email" type="email" class="form-control" name="Email" placeholder="Enter email" disabled="true">
          </div>
          <div class="form-group">
            <label for="gravatar">头像:</label>
            <input v-model="user.avatar" class="form-control disabled" name="gravatar" placeholder="Avatar" disabled="true">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button @click="updateUser(user)" class="btn btn-primary btn-lg btn-flat">提交</button>
        </div>
      </form>
      <div class="box-footer"></div>
      <form role="form">
        <div class="box-body">
          <div class="form-group">
            <label for="password">旧密码:</label>
            <input v-model="auth.password" type="password" class="form-control" name="password" placeholder="Password">
          </div>
          <div class="form-group">
            <label for="new_password">新密码:</label>
            <input v-model="auth.new_password" type="password" class="form-control" name="new_password" placeholder="New Password">
          </div>
          <div class="form-group">
            <label for="new_password_confirmation">确认新密码:</label>
            <input v-model="auth.new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm Password">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button @click="updatePass(auth)" class="btn btn-primary btn-lg btn-flat">提交</button>
        </div>
      </form>
    </div>
    <!-- /.box -->
</template>

<script>
import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

export default {
  created() {
    this.fetchUser()
  },
  data () {
    return {
      user: {
        name: '',
        username: '',
        email: '',
        avatar: '',
      },
      auth: {
        password: '',
        new_password: '',
        new_password_confirmation: '',
      },
    }
  },
  methods: {
    fetchUser () {
      this.$http({url: '/api/me', method: 'GET'}).then(function (response) {
        this.$set('user', response.data)
      })
    },
    updateUser (user) {
      event.preventDefault();
        this.$http.patch('/api/me', user).then(function (response) {
          show_stack_success('更新成功.', response)
        }, function (response){
          show_stack_error('更新失败', response)
        })
    },
    updatePass (auth) {
      event.preventDefault();
      this.$http.patch('/api/pass', auth).then(function (response) {
        show_stack_success('修改密码成功.', response);
        setTimeout("self.location.reload();", 800);
      }, function (response){
        show_stack_error('修改密码失败', response)
      })
    },
  },
}
</script>
