<template>
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-list"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Campaigns</span>
          <span class="info-box-number">{{campaigns.num}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!--<div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-orange"><i class="fa fa-th-large"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Categories</span>
          <span class="info-box-number">{{categories.length}}</span>
        </div>
        &lt;!&ndash; /.info-box-content &ndash;&gt;
      </div>
      &lt;!&ndash; /.info-box &ndash;&gt;
    </div>-->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Users</span>
          <span class="info-box-number">{{users.num}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  ready () {
   this.fetchCampaigns()
   this.fetchUsers()
  },
  data () {
    return {
      campaigns: [],
      users:[],
    }
  },
  methods: {
    fetchCampaigns () {
      this.$http({url: '/camp/list_num.json', method: 'GET'}).then(function (response) {
        this.$set('campaigns', response.data)
      })
    },
    fetchUsers() {
      this.$http({url: '/camp/user_num.json', method: 'GET'}).then(function (response) {
        this.$set('users', response.data)
      })
    },
  }
}
</script>
