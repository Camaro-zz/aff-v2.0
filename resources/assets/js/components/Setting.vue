<template>
<!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">常用设置</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form">
        <div class="box-body">
          <div class="form-group">
            <label for="openregister">开放注册:</label>
            开启<input type="radio" id="open" value="1" v-model="setting.open_register">
            关闭<input type="radio" id="close" value="0" v-model="setting.open_register">
          </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button @click="updateSetting(setting)" class="btn btn-primary btn-lg btn-flat">保存</button>
        </div>
      </form>
    </div>
    <!-- /.box -->
</template>

<script>
import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

export default {
  ready() {
    this.fetchSetting()
  },
  data () {
    return {
      setting: {
        open_register: 1,
      }
    }
  },
  methods: {
    fetchSetting () {
      this.$http({url: '/setting/info.json', method: 'GET'}).then(function (response) {
        this.$set('setting', response.data.data)
      })
    },
    updateSetting(){
      this.$http.post('/setting/update.json', this.setting).then((response) => {
        if(response.data.status == false){
          show_stack_error(response.data.msg, response)
        }else{
          show_stack_success('保存成功！', response)
        }
      }, function (response){
          show_stack_error('保存失败！', response)
      });
    }
  },
}
</script>
