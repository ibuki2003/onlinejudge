@extends('layouts.page')
@section('title', __('name.submissions.'.($me?'me':'all')))
@section('content')
<div id="table-controller">
    <div class="form-inline my-4">
        <div class="form-group m-2">
            <label for="filter_problem">{{__('ui.submission.problemId')}}</label>
            <select class="form-control" id="filter_problem" v-model="filter_problem">
                    <option value="">-</option>
                    @foreach($problems as $problem)
                        <option value="{{$problem->id}}">{{$problem->id.' : '.$problem->title}}</option>
                    @endforeach
            </select>
        </div>
        <div class="form-group m-2">
            <label for="filter_lang">{{__('ui.submission.lang')}}</label>
            <select class="form-control" id="filter_lang" v-model="filter_lang">
                    <option value="">-</option>
                    @foreach($langs as $lang)
                        <option value="{{$lang->id}}">{{$lang->name}}</option>
                    @endforeach
            </select>
        </div>
        <div class="form-group m-2">
            <label for="filter_status">{{__('ui.submission.status')}}</label>
            <select class="form-control" id="filter_status" v-model="filter_status">
                    <option value="">-</option>
                    <option v-for="(color, stat) in statusColors" v-bind:class="'text-'+color" v-bind:value="stat">@{{stat}}</option>
            </select>
        </div>
        @if(!$me)
        <div class="form-group m-2">
            <label for="filter_sender">{{__('ui.submission.sender')}}</label>
            <input type="text" class="form-control" id="filter_sender" v-model.text="filter_sender">
        </div>
        @endif
        <button class="btn btn-primary" v-on:click="updatefilter">{{__('ui.filter')}}</button>
    </div>
    <div class="form-inline my-4">
        <div class="form-group m-2">
            <input class="form-check-input" id="reload_enabled_check" type="checkbox" v-model="autoreload">
            <label class="form-check-label" for="reload_enabled_check">{{__('ui.autoreload')}}</label>
        </div>
        <button class="btn btn-secondary" v-on:click="reload">{{__('ui.reload')}}</button>
    </div>
    <div>
        <output>@{{lastupdate}}</output>
        <i class="loading" v-if="loading"></i>
        <i class="error" v-else-if="fail"></i>
        <i class="done" v-else></i>
    </div>
    <div class="table-responsive">
        <output id="last"></output><span id="stat"></span>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('ui.submission.problemId')}}</th>
                    <th scope="col">{{__('ui.submission.sender')}}</th>
                    <th scope="col">{{__('ui.submission.lang')}}</th>
                    <th scope="col">{{__('ui.submission.point')}}</th>
                    <th scope="col">{{__('ui.submission.size')}}</th>
                    <th scope="col">{{__('ui.submission.time')}}</th>
                    <th scope="col">{{__('ui.submission.exec_time')}}</th>
                    <th scope="col">{{__('ui.submission.status')}}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in data" v-bind:class="'table-'+statusColors[row.status]" v-bind:key="row.id">
                    <td>@{{row.id}}</td>
                    <td>
                        <a v-bind:href="'/problems/'+row.problem.id"
                            v-bind:title="'#'+row.problem.id+' '+row.problem.title+'<br>'+row.problem.user_id"
                            data-toggle="tooltip" data-html="true">@{{row.problem.id}}</a>
                    </td>
                    <td>@{{row.sender}}</td>
                    <td>@{{row.lang}}</td>
                    <td>@{{row.point}}</td>
                    <td>@{{row.size}}</td>
                    <td>@{{row.time}}</td>
                    <td>@{{row.exec_time !== null ? row.exec_time+'ms' : '--'}}</td>
                    <td>@{{row.status}}</td>
                    <td><a v-bind:href="'/submissions/'+row.id">{{__('ui.submission.detail')}}</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <paginate-link v-model="current_page" v-bind:last="last_page" v-on:input="reload();set_hash();"></paginate-link>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('css/loadicon.css')}}">
@endsection

@section('script')
<script>
    function getHashParams() {
        var hashParams = {};
        var e, q = window.location.hash.substring(1);
        var re = /([^&;=]+)=?([^&;]*)/g;
        function d (s) {return decodeURIComponent(s.replace(/\+/g, " "));}
        while (e = re.exec(q))
            hashParams[d(e[1])] = d(e[2]);
        return hashParams;
    }

    function setHashParams(obj) {
        var params=[];
        for (var key in obj){
            if(obj[key]!='')
                params.push(key+'='+obj[key]);
        }
        location.hash=params.join('&');
    }
    new Vue({
        el: '#table-controller',
        data: {
            filter: {},
            filter_problem: '', filter_lang: '', filter_status: '', filter_sender: '',
            data: [],
            current_page: 1,
            last_page: 1,
            url: '/api/submissions{{$me?'/me':''}}',
            statusColors:{
                @foreach (config('oj.status_color') as $key=>$data)
                {{$key}}:'{{$data}}',
                @endforeach
            },
            lastupdate: '',
            loading: false,
            fail: false,
            autoreload: true,
        },
        methods: {
            reload: function(){
                if(this.loading)return;
                this.loading=true;
                $.getJSON(this.url, this.parameters)
                .done(function(data){
                    this.data=data.data;
                    this.last_page=data.meta.last_page;
                    var date = new Date();
                    this.lastupdate = ( '000' + date.getFullYear() ).slice( -4 ) + '/' +
                        ( '0' + (date.getMonth()+1) ).slice( -2 ) + '/' +
                        ( '0' + date.getDate() ).slice( -2 ) + ' ' +
                        ( '0' + date.getHours() ).slice( -2 ) + ':' +
                        ( '0' + date.getMinutes() ).slice( -2 ) + ':' +
                        ( '0' + date.getSeconds() ).slice( -2 );
                    this.fail=false;
                }.bind(this))

                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    this.fail=true;
                }.bind(this))

                .always(function() {
                    this.loading=false;
                }.bind(this));
            },
            updatefilter: function(){
                var filter={};
                
                if(this.filter_problem!='')filter.problem_id=this.filter_problem;
                if(this.filter_lang   !='')filter.lang_id   =this.filter_lang;
                if(this.filter_status !='')filter.status    =this.filter_status;
                if(this.filter_sender !='')filter.user_id   =this.filter_sender;
                this.filter=filter;
                this.reload();
                this.set_hash();
            },
            set_hash: function(){
                setHashParams(Object.assign({page: this.current_page},this.filter));
            }
        },
        computed: {
            parameters: function(){
                return Object.assign({page: this.current_page},this.filter);
            }
        },
        created: function(){
            if(location.hash){
                var params=getHashParams();
                if(params.problem)this.filter_problem=params.problem;
                if(params.lang   )this.filter_lang   =params.lang;
                if(params.status )this.filter_status =params.status;
                if(params.sender )this.filter_sender =params.sender;
                if(params.page)this.current_page=parseInt(params.page);
                this.updatefilter();
            }else{
                this.reload();
            }

            setInterval(function(){
                if(this.autoreload)this.reload()
            }.bind(this),5000);
        },
        updated: function(){
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
</script>
@endsection
