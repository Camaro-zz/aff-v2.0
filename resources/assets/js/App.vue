<template>
<div >
  <navbar></navbar>
  <sidebar></sidebar>
</div>
  <div id="app">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Admin Panel
          <small>Everything is here.</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Your Page Content Here -->
        <!-- route outlet -->
        <router-view></router-view>

        <div class="alert {{alert_class}} fade fixed {{show_class}}">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           {{msg}}
        </div>
      </section>
      <!-- /.content -->
    </div>
  </div>
</template>


<script>
import Navbar from './components/layouts/Navbar.vue'
import Sidebar from './components/layouts/Sidebar.vue'



export default {
  components: {
    Navbar,
    Sidebar
  },
  data(){
    return {
      msg: 'test',
      show_class: 'out',
      alert_class: 'alert-success',
    }
  },
  methods: {
    changeAlert(type, message){
      if(type == 0){
        this.show_class = 'out';
      }else{
        this.show_class = 'in';
      }
      this.msg = message;
    }
  },
  replace: false
}
</script>

<style>
  .fixed {
    position: fixed;
    top: 50px;
    width: 30%;
    right: 30%;
  }
</style>
