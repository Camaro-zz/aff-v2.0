<template>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
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
                        <button type="button" @click="updateCampaigns(camp)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-->
    </div>
    <!-- ./row -->
</section>

<section class="content">
    <h1>LPs</h1>
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
                            <th>LP ID</th>
                            <th>标题</th>
                            <th>LP链接</>
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
                            <td class="col-md-1">{{lp.cvr_rate}}</td>
                            <td class="col-md-1"><input class="i-class-col" v-bind:value="lp.lp_weight">%</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body pad">
                    <button type="button" @click="updateLPs(lps)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
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
                            <th>Offer链接</>
                            <th>点击量</th>
                            <th>转化量</th>
                            <th>转化率</th>
                            <th>占比</th>
                        </tr>
                        <tr v-for="offer in offers">
                            <td class="col-md-1">{{offer.offer_id}}</td>
                            <td class="col-md-2">{{offer.offer_name}}</td>
                            <td class="col-md-4">{{offer.offer_url}}</td>
                            <td class="col-md-1">{{offer.clicks}}</td>
                            <td class="col-md-1">{{offer.cvrs}}</td>
                            <td class="col-md-1">{{offer.cvr_rate}}</td>
                            <td class="col-md-1"><input class="i-class-col" v-bind:value="offer.offer_weight">%</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body pad">
                    <button type="button" @click="updateOffers(offers)" class="btn btn-lg btn-primary btn-flat pull-right">保存</button>
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
        this.fetchLPs()
        this.fetchCamp()
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
            lps: [],
            offers: []
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
        fetchLPs () {
            this.$http({url: '/camp/' + this.camp_id + '/lp.json', method: 'GET'}).then(function (response) {
                this.$set('lps', response.data)
            })
        },
        updateCampaigns(camp){
            this.$http.put('/camp/edit/' + this.camp_id + '.json', camp).then((response) => {
                show_stack_success('保存成功！', response)
            }, function (response){
                show_stack_error('保存失败！', response)
            });
        },
        updateOffers(offers){
            console.log(offers);
        }
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
