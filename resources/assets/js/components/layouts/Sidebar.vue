<template>
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    {{creatingPost}}
      <!-- Sidebar user panel (optional) -->
      <div v-link="{ path: '/profile' }" class="user-panel">
        <div class="pull-left image">
          <img class="profile-user-img img-responsive img-circle"
          :src="user.avatar" alt="User profile picture">
        </div>
        <div class="pull-left info">
          <p>{{user.name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li><a v-link="{ path: '/' }"><i class="fa fa-home"></i> <span>Home</span></a></li>
        <!-- Optionally, you can add icons to the links -->
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i> <span>Campaigns</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a v-link="{ path: '/posts' }"><i class="fa fa-tasks"></i>Index</a></li>
            <li><a @click="createPost" href="#"><i class="fa fa-keyboard-o"></i>Create post</a></li>
          </ul>
        </li>-->
        <li><a v-link="{ path: '/campaigns' }"><i class="fa fa-th-list"></i> <span>Campaigns</span></a></li>
        <template v-if="user.level == 9">
        <li><a v-link="{ path: '/users' }"><i class="fa fa-users"></i> <span>Users</span></a></li>
        <li><a v-link="{ path: '/setting' }"><i class="fa fa-users"></i> <span>Setting</span></a></li>
          <li><a v-link="{ path: '/logs' }"><i class="fa fa-users"></i> <span>操作日志</span></a></li>
        </template>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="control-sidebar-bg"></div>
</template>

<script>
import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../../Pnotice.js'

export default {
  created() {
    this.fetchUser()
  },
  data () {
    return {
      user: {}
    }
  },
  computed: {

  },
  methods: {
    fetchUser () {
      this.$http({url: '/api/me', method: 'GET'}).then(function (response) {
        this.$set('user', response.data)
      })
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
  color: #42b983;
}
aside {
  position: fixed;
  height: 100%;
}
.user-panel {
  cursor: pointer;
}
</style>
