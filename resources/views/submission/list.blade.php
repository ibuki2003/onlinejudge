@extends('layouts.page')
@section('title', __('name.submissions.'.($me?'me':'all')))
@section('content')
<div id="table-controller">
    <div class="form-inline my-4">
        <div class="form-group m-2">
            <label for="filter_problem">{{__('ui.submission.problemId')}}</label>
            <input type="number" class="form-control" id="filter_problem" min="1" v-model.number="filter_problem">
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
                    <th scope="col">{{__('ui.submission.status')}}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in data.data" v-bind:class="'table-'+statusColors[row.status]">
                    <td>@{{row.id}}</td>
                    <td><a v-bind:href="'/problems/'+row.problem">@{{row.problem}}</a></td>
                    <td>@{{row.sender}}</td>
                    <td>@{{row.lang}}</td>
                    <td>@{{row.point}}</td>
                    <td>@{{row.size}}</td>
                    <td>@{{row.time.date}}</td>
                    <td>@{{row.status}}</td>
                    <td><a v-bind:href="'/submissions/'+row.id">{{__('ui.submission.detail')}}</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p>@{{data.meta.current_page}}/@{{data.meta.last_page}}</p>
    <button class="btn" v-on:click="prev" v-bind:disabled="data.links.prev===null">{{__('pagination.previous')}}</button>
    <button class="btn" v-on:click="next" v-bind:disabled="data.links.next===null">{{__('pagination.next')}}</button>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('css/loadicon.css')}}">
@endsection

@section('script')
<script>
    new Vue({
        el: '#table-controller',
        data: {
            interval: null,
            filter: {},
            filter_problem: '', filter_lang: '', filter_status: '', filter_sender: '',
            data: {
                data: [],
                links: {prev:null, next: null},
                meta: {current_page:1,last_page:0}
            },
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
                $.getJSON(this.url, this.filter)
                .done(function(data){
                    this.data=data;
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
            prev: function(){
                this.url=this.data.links.prev || this.url;
                this.reload();
            },
            next: function(){
                this.url=this.data.links.next || this.url;
                this.reload();
            },
            updatefilter: function(){
                var filter={};
                if(this.filter_problem!='')filter.problem=filter_problem.value;
                if(this.filter_lang   !='')filter.lang   =filter_lang.value;
                if(this.filter_status !='')filter.status =filter_status.value;
                if(this.filter_sender !='')filter.sender =filter_sender.value;
                this.filter=filter;
                this.reload();
            }
        },
        created: function(){
            setInterval(function(){
                if(this.autoreload)this.reload()
            }.bind(this),5000);
            this.reload();
        }
    });
</script>
@endsection
