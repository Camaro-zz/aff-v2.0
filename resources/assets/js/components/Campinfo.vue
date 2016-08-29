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
            <option value='0'>请选择</option>
        <template v-for="token in tokens">
            <option value="{{$key}}">{{token}}</option>
        </template>
    </select>
    <select name="zoneFilter" id="zoneFilter" class="select" onchange="CampaignsFilter(true,0,1);">
        <option value="0">Default Time - Asia/Shanghai</option><option value="1">(GMT -11:00) Pacific/Samoa</option>
        <option value="2">(GMT -10:00) Pacific/Honolulu</option>
        <option value="3">(GMT -08:00) America/Anchorage</option>
        <option value="4">(GMT -07:00) America/Los_Angeles</option>
        <option value="5">(GMT -07:00) America/Phoenix</option>
        <option value="6">(GMT -06:00) America/Edmonton</option>
        <option value="7">(GMT -06:00) America/Denver</option>
        <option value="8">(GMT -06:00) America/Mazatlan</option>
        <option value="9">(GMT -06:00) America/Chihuahua</option>
        <option value="10">(GMT -06:00) America/Regina</option>
        <option value="11">(GMT -05:00) America/Chicago</option>
        <option value="12">(GMT -06:00) America/Managua</option>
        <option value="13">(GMT -06:00) America/Costa_Rica</option>
        <option value="14">(GMT -05:00) America/Mexico_City</option>
        <option value="15">(GMT -06:00) America/Belize</option>
        <option value="16">(GMT -06:00) America/Guatemala</option>
        <option value="17">(GMT -06:00) America/El_Salvador</option>
        <option value="18">(GMT -05:00) America/Bogota</option>
        <option value="19">(GMT -05:00) America/Lima</option>
        <option value="20">(GMT -04:00) America/Indianapolis</option>
        <option value="21">(GMT -04:00) America/Toronto</option>
        <option value="22">(GMT -04:00) America/New_York</option>
        <option value="23">(GMT -04:00) America/Havana</option>
        <option value="24">(GMT -05:00) America/Panama</option>
        <option value="25">(GMT -04:00) America/Port-au-Prince</option>
        <option value="26">(GMT -04:30) America/Caracas</option>
        <option value="27">(GMT -04:00) America/La_Paz</option>
        <option value="28">(GMT -04:00) America/Manaus</option>
        <option value="29">(GMT -03:00) America/Goose_Bay</option>
        <option value="30">(GMT -03:00) America/Halifax</option>
        <option value="31">(GMT -04:00) America/Aruba</option>
        <option value="32">(GMT -04:00) America/Barbados</option>
        <option value="33">(GMT -04:00) America/Dominica</option>
        <option value="34">(GMT -04:00) America/Puerto_Rico</option>
        <option value="35">(GMT -04:00) America/Santo_Domingo</option>
        <option value="36">(GMT -04:00) America/St_Thomas</option>
        <option value="37">(GMT -03:00) Atlantic/Bermuda</option>
        <option value="38">(GMT -02:30) America/St_Johns</option>
        <option value="39">(GMT -04:00) America/Asuncion</option>
        <option value="40">(GMT -03:00) America/Belem</option>
        <option value="41">(GMT -03:00) America/Buenos_Aires</option>
        <option value="42">(GMT -02:00) America/Godthab</option>
        <option value="43">(GMT -04:00) America/Santiago</option>
        <option value="44">(GMT -03:00) America/Sao_Paulo</option>
        <option value="45">(GMT -03:00) America/Montevideo</option>
        <option value="46">(GMT -02:00) America/Noronha</option>
        <option value="47">(GMT -02:00) Atlantic/South_Georgia</option>
        <option value="48">(GMT +00:00) Atlantic/Azores</option>
        <option value="49">(GMT +00:00) UTC</option>
        <option value="50">(GMT +01:00) Europe/London</option>
        <option value="51">(GMT +01:00) Europe/Belfast</option>
        <option value="52">(GMT +01:00) Europe/Lisbon</option>
        <option value="53">(GMT +00:00) Atlantic/Reykjavik</option>
        <option value="54">(GMT +01:00) Africa/Casablanca</option>
        <option value="55">(GMT +00:00) Africa/Freetown</option>
        <option value="56">(GMT +01:00) Africa/Luanda</option>
        <option value="57">(GMT +02:00) Africa/Tripoli</option>
        <option value="58">(GMT +02:00) Europe/Belgrade</option>
        <option value="59">(GMT +02:00) Europe/Berlin</option>
        <option value="60">(GMT +02:00) Europe/Brussels</option>
        <option value="61">(GMT +02:00) Europe/Budapest</option>
        <option value="62">(GMT +02:00) Europe/Copenhagen</option>
        <option value="63">(GMT +02:00) Europe/Luxembourg</option>
        <option value="64">(GMT +02:00) Europe/Madrid</option>
        <option value="65">(GMT +02:00) Europe/Oslo</option>
        <option value="66">(GMT +02:00) Europe/Paris</option>
        <option value="67">(GMT +02:00) Europe/Prague</option>
        <option value="68">(GMT +02:00) Europe/Rome</option>
        <option value="69">(GMT +02:00) Europe/Stockholm</option>
        <option value="70">(GMT +02:00) Europe/Vienna</option>
        <option value="71">(GMT +02:00) Europe/Warsaw</option>
        <option value="72">(GMT +02:00) Europe/Zurich</option>
        <option value="73">(GMT +03:00) Europe/Athens</option>
        <option value="74">(GMT +03:00) Europe/Bucharest</option>
        <option value="75">(GMT +03:00) Europe/Helsinki</option>
        <option value="76">(GMT +03:00) Europe/Istanbul</option>
        <option value="77">(GMT +03:00) Europe/Kiev</option>
        <option value="78">(GMT +03:00) Asia/Beirut</option>
        <option value="79">(GMT +03:00) Asia/Tel_Aviv</option>
        <option value="80">(GMT +03:00) Asia/Istanbul</option>
        <option value="81">(GMT +02:00) Africa/Cairo</option>
        <option value="82">(GMT +02:00) Africa/Gaborone</option>
        <option value="83">(GMT +03:00) Africa/Asmara</option>
        <option value="84">(GMT +03:00) Africa/Kampala</option>
        <option value="85">(GMT +03:00) Africa/Mogadishu</option>
        <option value="86">(GMT +03:00) Africa/Nairobi</option>
        <option value="87">(GMT +03:00) Asia/Amman</option>
        <option value="88">(GMT +03:00) Asia/Baghdad</option>
        <option value="89">(GMT +04:30) Asia/Tehran</option>
        <option value="90">(GMT +03:00) Asia/Bahrain</option>
        <option value="91">(GMT +03:00) Asia/Kuwait</option>
        <option value="92">(GMT +03:00) Asia/Qatar</option>
        <option value="93">(GMT +04:00) Europe/Moscow</option>
        <option value="94">(GMT +04:00) Asia/Dubai</option>
        <option value="95">(GMT +04:00) Asia/Muscat</option>
        <option value="96">(GMT +05:00) Indian/Maldives</option>
        <option value="97">(GMT +05:00) Asia/Karachi</option>
        <option value="98">(GMT +05:30) Asia/Calcutta</option>
        <option value="99">(GMT +05:45) Asia/Katmandu</option>
        <option value="100">(GMT +06:00) Asia/Dhaka</option>
        <option value="101">(GMT +06:30) Asia/Rangoon</option>
        <option value="102">(GMT +07:00) Indian/Christmas</option>
        <option value="103">(GMT +07:00) Asia/Bangkok</option>
        <option value="104">(GMT +07:00) Asia/Ho_Chi_Minh</option>
        <option value="105">(GMT +07:00) Asia/Jakarta</option>
        <option value="106">(GMT +08:00) Asia/Hong_Kong</option>
        <option value="107">(GMT +08:00) Asia/Kuala_Lumpur</option>
        <option value="108">(GMT +08:00) Asia/Macau</option>
        <option value="109">(GMT +08:00) Asia/Manila</option>
        <option value="110">(GMT +08:00) Asia/Shanghai</option>
        <option value="111">(GMT +08:00) Asia/Singapore</option>
        <option value="112">(GMT +08:00) Asia/Taipei</option>
        <option value="113">(GMT +08:00) Australia/Perth</option>
        <option value="114">(GMT +09:00) Asia/Seoul</option>
        <option value="115">(GMT +09:00) Asia/Tokyo</option>
        <option value="116">(GMT +09:30) Australia/Darwin</option>
        <option value="117">(GMT +10:00) Pacific/Guam</option>
        <option value="118">(GMT +10:00) Australia/Brisbane</option>
        <option value="119">(GMT +10:00) Australia/Queensland</option>
        <option value="120">(GMT +09:30) Australia/Adelaide</option>
        <option value="121">(GMT +10:00) Australia/Victoria</option>
        <option value="122">(GMT +10:00) Australia/Melbourne</option>
        <option value="123">(GMT +10:00) Australia/Sydney</option>
        <option value="124">(GMT +11:30) Pacific/Norfolk</option>
        <option value="125">(GMT +12:00) Asia/Magadan</option>
        <option value="126">(GMT +12:00) Pacific/Wake</option>
        <option value="127">(GMT +12:00) Pacific/Fiji</option>
        <option value="128">(GMT +12:00) Pacific/Auckland</option>
        <option value="129">(GMT +13:00) Pacific/Apia</option></select>
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
                }else{
                    this.$set('token_group_data', [])
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
