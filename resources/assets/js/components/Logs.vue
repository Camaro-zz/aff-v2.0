<template>
    <h1>操作日志</h1>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">操作日志</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>账号</th>
                                <th>姓名</th>
                                <th>行为</th>
                                <th>时间</th>
                            </tr>
                            <tr v-for="log in logs">
                                <td>{{log.username}}</td>
                                <td>{{log.name}}</td>
                                <td>{{log.memo}}</td>
                                <td>{{log.created_at}}</td>
                            </tr>
                        </table>
                        <div class="page-bar">
                            <ul>
                                <li><a v-on:click="goPage(0)">上一页</a></li>
                                <li v-for="index in indexs"  v-bind:class="{ 'active': cur == index}">
                                    <a v-on:click="btnClick(index)">{{ index }}</a>
                                </li>
                                <li><a v-on:click="goPage(1)">下一页</a></li>
                                <li><a>共<i>{{all}}</i>页</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
</template>

<style>
    ul,li{
        margin: 0px;
        padding: 0px;
    }
    li{
        list-style: none
    }
    .page-bar li:first-child>a {
        margin-left: 0px
    }
    .page-bar a{
        border: 1px solid #ddd;
        text-decoration: none;
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #337ab7;
        cursor: pointer
    }
    .page-bar a:hover{
        background-color: #eee;
    }
    .page-bar .active a{
        color: #fff;
        cursor: default;
        background-color: #337ab7;
        border-color: #337ab7;
    }
    .page-bar i{
        font-style:normal;
        color: #d44950;
        margin: 0px 4px;
        font-size: 12px;
    }
</style>

<script>
    export default {
        props: ['cur', 'all'],
        ready () {
            this.showLogs()
        },
        data () {
            return {
                logs: [],
                cur: 1,
                all:0,
            }
        },
        methods:{
            showLogs(){
                var page = this.cur ? this.cur : 1;
                this.$http({url: '/camp/logs.json?page='+page, method: 'GET'}).then(function (response) {
                    this.$set('logs', response.data.data)
                    this.$set('all', response.data.count)
                    this.$set('cur', response.data.page)
                })
            },
            goPage(type){
                if(type == 1){
                    if(this.cur < this.all){
                        this.cur++
                    }
                }else{
                    if(this.cur > 1){
                        this.cur--
                    }
                }
                this.showLogs()
            },
            btnClick: function(data){
                if(data != this.cur){
                    this.cur = data
                    this.$dispatch('btn-click',data)
                    this.showLogs()
                }
            },
        },
        computed: {
            indexs: function(){
                var left = 1
                var right = this.all
                var ar = []
                if(this.all>= 11){
                    if(this.cur > 5 && this.cur < this.all-4){
                        left = this.cur - 5
                        right = this.cur + 4
                    }else{
                        if(this.cur<=5){
                            left = 1
                            right = 10
                        }else{
                            right = this.all
                            left = this.all -9
                        }
                    }
                }
                while (left <= right){
                    ar.push(left)
                    left ++
                }
                return ar
            }
        },
    }
</script>

<style>
    body {
        font-family: Helvetica, sans-serif;
    }
</style>
