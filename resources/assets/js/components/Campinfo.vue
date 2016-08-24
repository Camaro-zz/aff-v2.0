<template>
<link rel="stylesheet" href="/css/simplemde.min.css">
<section class="content">
    <h1>Campaign Info</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Capmaigns info
                        <small>Markdown Editor</small>
                    </h3>
                </div>
                <div class="box-body pad">
                    <div class="form-horizontal" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="title" class="col-sm-1 control-label">活动标题</label>
                        <div class="col-sm-11">
                          <input class="form-control" id="title" placeholder="title" v-model="camp.camp_name">
                        </div>
                      </div>

                    <table class="table table-hover">
                        <tr>
                            <td>
                                <button type="button" @click="updateCampaigns(camp)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
                            </td>
                        </tr>
                    </table>

                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-->
    </div>
    <!-- ./row -->
</section>

<!--<section class="content">
    <h1>LPs</h1>
    &lt;!&ndash;<button type="button" @click="createPost" class="btn btn-lg btn-primary btn-flat" style="margin-bottom: 15px;">
      Create Post
    </button>&ndash;&gt;
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                &lt;!&ndash; /.box-header &ndash;&gt;
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>LP ID</th>
                            <th>标题</th>
                            <th>LP链接</th>
                            <th>展示量</th>
                            <th>点击量</th>
                            <th>转化量</th>
                            <th>转化率</th>
                            <th>占比</th>
                        </tr>
                        <tr v-for="lp in lps">
                            <td class="col-md-1">{{lp.lp_id}}</td>
                            <td class="col-md-2">{{lp.lp_name}}</td>
                            <td class="col-md-4">{{lp.lp_url}}</td>
                            <td class="col-md-1">{{lp.views}}</td>
                            <td class="col-md-1">{{lp.clicks}}</td>
                            <td class="col-md-1">{{lp.cvrs}}</td>
                            <td class="col-md-1">{{lp.cvr_rate}}%</td>
                            <td class="col-md-1"><input class="i-class-col" onkeyup="if(this.value.length==1){this.value = this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" v-model="lp.lp_weight">%</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body pad">
                    <button type="button" @click="updateLPs(lps)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
                </div>
                &lt;!&ndash; /.box-body &ndash;&gt;
            </div>
            &lt;!&ndash; /.box &ndash;&gt;
        </div>
    </div>
</section>-->
<section class="content">
    <select class="select" v-on:change="selectToken()" v-model='selectd_token'>
        <template v-for="token in tokens">
            <option value="{{$key}}">{{token}}</option>
        </template>
    </select>
</section>

<section class="content">
    <h1>Offers</h1>
    <!--<button type="button" @click="createPost" class="btn btn-lg btn-primary btn-flat" style="margin-bottom: 15px;">
      Create Post
    </button>-->
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Offer ID</th>
                            <th>标题</th>
                            <!--<th>Offer链接</>-->
                            <th>单价</th>
                            <th>总收入</th>
                            <th>点击量</th>
                            <th>转化量</th>
                            <th>转化率</th>
                            <th>占比</th>
                        </tr>
                        <tr v-for="offer in offers">
                            <td class="col-md-1">{{offer.offer_id}}</td>
                            <td class="col-md-1">{{offer.offer_name}}</td>
                            <!--<td class="col-md-4">{{offer.offer_url}}</td>-->
                            <td class="col-md-1">{{offer.offer_payout}}</td>
                            <td class="col-md-1">{{offer.offer_all_payout}}</td>
                            <td class="col-md-1">{{offer.clicks}}</td>
                            <td class="col-md-1">{{offer.cvrs}}</td>
                            <td class="col-md-1">{{offer.cvr_rate}}%</td>
                            <td class="col-md-1"><input class="i-class-col" onkeyup="if(this.value.length==1){this.value = this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"  v-model="offer.offer_weight">%</td>
                        </tr>


                    </table>
                    <table class="table table-hover">
                        <tr>
                            <td>
                                <button type="button" @click="updateOffers(offers)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
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

<section class="content" v-show="token_group_data.length">
    <h1>Group By {{tokens[selectd_token]}}</h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>{{tokens[selectd_token]}}</th>
                            <th>Clicks(点击量)</th>
                            <th>LP Views</th>
                            <th>LP Clicks</th>
                            <th>LP CTR</th>
                            <th>Leads</th>
                            <th>Offer CVR</th>
                            <th>LP CVR</th>
                            <th>CPC</th>
                        </tr>
                        <tr v-for="token_data in token_group_data">
                            <td class="col-md-1">{{token_data.token}}</td>
                            <td class="col-md-1">{{token_data.clicks}}</td>
                            <td class="col-md-1">{{token_data.lp_views}}</td>
                            <td class="col-md-1">{{token_data.lp_clicks}}</td>
                            <td class="col-md-1">{{token_data.lp_ctr}}</td>
                            <td class="col-md-1">{{token_data.leads}}</td>
                            <td class="col-md-1">{{token_data.offer_cvr}}%</td>
                            <td class="col-md-1">{{token_data.lp_cvr}}%</td>
                            <td class="col-md-1">{{token_data.CPC}}</td>
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

<style>
    .content{min-height:0}
    .i-class-col{
        width:30%;
    }
</style>

<script>
import SimpleMDE from 'simplemde'
import Dropzone from './Dropzone.vue'
import Multiselect from 'vue-multiselect/lib/vue-multiselect.js'
import { stack_bottomright, show_stack_success, show_stack_error } from '../Pnotice.js'

export default {
    created() {
        this.camp_id = this.$route.params.hashid
        this.fetchOffers()
        //this.fetchLPs()
        this.fetchCamp()
        this.fetchTokens()
    },
    components: {
      Dropzone,
      Multiselect
    },
    ready(){
        this.simplemde = new SimpleMDE({
            element: document.getElementById("mdeditor"),
            spellChecker: false,
        });
    },
    data () {
        return {
            camp: [],
            //lps: [],
            offers: [],
            tokens: [],
            selectd_token: [],
            token_group_data: [],
        }
    },
    methods: {
        fetchCamp(){
            this.$http({url: '/camp/info/' + this.camp_id + '.json', method: 'GET'}).then(function (response) {
                this.$set('camp', response.data)
            })
        },
        fetchOffers () {
            this.$http({url: '/camp/' + this.camp_id + '/offer.json', method: 'GET'}).then(function (response) {
                this.$set('offers', response.data)
            })
        },
        fetchTokens(){
            this.$http({url: '/camp/tokens/' + this.camp_id + '.json', method: 'GET'}).then(function (response) {
                if(response.data.status != 'false'){
                    this.$set('tokens', response.data)
                }

            })
        },
        /*fetchLPs () {
            this.$http({url: '/camp/' + this.camp_id + '/lp.json', method: 'GET'}).then(function (response) {
                this.$set('lps', response.data)
            })
        },*/
        updateCampaigns(camp){
            this.$http.put('/camp/edit/' + this.camp_id + '.json', camp).then((response) => {
                show_stack_success('保存成功！', response)
            }, function (response){
                show_stack_error('保存失败！', response)
            });
        },
        updateOffers(offers){
            var data = {};
            $.each(offers,function(n,i){
               data[i.offer_id] = i.offer_weight;
            })
            data = JSON.stringify(data);
            this.$http.put('/camp/offer.json', data).then((response) => {
                if(response.data.status == true){
                    show_stack_success('保存成功！', response)
                }else{
                show_stack_error(response.data.msg, response)
                }
            }, function (response){
                show_stack_error('保存失败！', response)
            });
        },
        /*updateLPs(lps){
            var data = {};
            $.each(lps,function(n,i){
                data[i.lp_id] = i.lp_weight;
            })
            data = JSON.stringify(data);
            this.$http.put('/camp/lp.json', data).then((response) => {
                if(response.data.status == true){
                    show_stack_success('保存成功！', response)
                }else{
                    show_stack_error(response.data.msg, response)
                }
            }, function (response){
                show_stack_error('保存失败！', response)
            });
        }*/

        selectToken(){
            this.$http({url: '/camp/tokens/info/' + this.camp_id + '.json?token_id='+ this.selectd_token, method: 'GET'}).then(function (response) {
                if(response.data.status != 'false'){
                    this.$set('token_group_data', response.data.data)
                }
            })
        },
    },
    computed: {
      isPublished: function () {
          if (this.post.status !== 'approved' ) {
            return true
          } else {
            return false
          }
      }
    }
}
</script>
