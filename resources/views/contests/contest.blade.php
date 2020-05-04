@extends('layouts.page')
@section('title', $contest->title)
@section('content')
@csrf
<p style="white-space: pre-wrap;">{{$contest->description}}</p>
<h2>{{__('ui.contest.creator')}}:{{$contest->creator}}</h2>
<br>
<p>{{__('ui.contest.open')}} : {{$contest->start_time}}</p>
<p>{{__('ui.contest.close')}} : {{$contest->end_time}}</p>
<p>{{__('ui.contest.penalty')}} : {{$contest->penalty . __('ui.minutes')}}</p>

<p class="alert alert-success">{{(strtotime(date("Y-m-d H:i:s")) < strtotime($contest->start_time)) ? __('ui.contest.not_started') :
                                 ((strtotime(date("Y-m-d H:i:s")) < strtotime($contest->end_time)) ? __('ui.contest.in_progress') :
                                 __('ui.contest.ended'))}}</p>


@if (auth()->check() && $contest->can_participate())
<form method="post" name="form_participate" action="{{route('contest_participate', ['id' => $contest->id])}}">
    @csrf
    <p><button type="submit" class="btn btn-primary">{{__('ui.contest.participate')}}</button></p>
</form>
@endif
@if (auth()->check() && $contest->can_cancel_participate())
<form method="post" name="form_cancel_participate" action="{{route('contest_cancel_participate', ['id' => $contest->id])}}">
    @csrf
    <p><button type="submit" class="btn btn-danger">{{__('ui.contest.cancel_participate')}}</button></p>
</form>
@endif
@if (auth()->check() && auth()->id() == $contest->creator)
    <p><a href="/contests/{{$contest->id}}/edit" class="btn btn-dark" >{{__('name.contest.edit')}}</a></p>
@endif
<hr>
<div id="table-controller">
    <h2>{{__('ui.contest.problems')}}</h2>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">{{__('ui.problem.title')}}</th>
                <th scope="col">{{__('ui.problem.creator')}}</th>
                <th scope="col">{{__('ui.problem.difficulty')}}</th>
                <th scope="col">{{__('ui.contest.point_allotted')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="problem in data['problems']" :href="'/problems/' + problem.id">
                    <th scope="row">@{{problem.id}}</th>
                    <td><a :href="'/problems/' + problem.id">@{{problem.title}}</a></td>
                    <td>@{{problem.user_id}}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="'width:' + problem.difficulty*100/{{config('oj.difficulty_max')}} + '%'" :aria-valuenow="problem.difficulty" aria-valuemin="0" aria-valuemax="{{config('oj.difficulty_max')}}">@{{problem.difficulty}}</div>
                        </div>
                    </td>
                    <td>@{{problem.point}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2>{{__('ui.contest.standings')}}</h2>
    <div class="table-responsive">
        <output id="last"></output><span id="stat"></span>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">{{__('ui.contest.rank')}}</th>
                    <th scope="col">{{__('ui.contest.user_id')}}</th>
                    <th scope="col">{{__('ui.contest.point')}}</th>
                    <th scope="col" v-for="problem in data.problems"><a :href="'/problems/' + problem.id">@{{problem.title}}</a></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in data['users']" v-bind:key="row.id">
                    <td>@{{row.rank}}</td>
                    <td>@{{row.id}}</td>
                    <td><span class="text-info">@{{row.score_sum}}</span>
                        <span v-if="row.penalty_sum != -1" v-bind:class="{'text-danger' : row.penalty_sum > 0, 'text-muted' : row.penalty_sum == 0}">(@{{row.penalty_sum}})</span>
                        <br>@{{row.time_all}}
                    </td>
                    <td v-for="(data_one, index) in row.data" v-bind:key="index"
                        v-bind:class="getColorClass(data_one.score, data.problems[index].point, data_one.penalty)">
                        <span class="">@{{data_one.score}}</span>
                        <span v-if="data_one.penalty != -1" v-bind:class="{'text-danger' : data_one.penalty > 0, 'text-muted' : data_one.penalty == 0}">(@{{data_one.penalty}})</span>
                        <i v-if="data_one.judging" class="fas fa-hourglass-half"></i>
                        <br>@{{data_one.time}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@endsection

@section('style')
<style>
.progress{min-width:60px;}
tbody>tr{cursor: pointer;}
</style>
@endsection


@section('script')
<script>
    new Vue({
        el: '#table-controller',
        data: {
            data: [],
            url: '/api/contests/standings/{{$contest->id}}',
            lastupdate: '',
            loading: false,
            fail: false,
            autoreload: true,
        },
        methods: {
            reload: function(){
                if(this.loading)return;
                this.loading=true;
                $.getJSON(this.url)
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
                    console.log(errorThrown);
                    this.fail=true;
                }.bind(this))

                .always(function() {
                    this.loading=false;
                }.bind(this));
            },
            getColorClass : function(point, point_allotted, penalty) {
                if (point == point_allotted) return 'table-success';
                else if (point > 0) return 'table-warning';
                else if (penalty != -1) return 'table-danger';
                else return '';
            }
        },
        computed: {
            parameters: function(){
                return Object.assign({page: this.current_page},this.filter);
            }
        },
        created: function(){
            setInterval(function(){
                if(this.autoreload)this.reload()
            }.bind(this),10000);
            this.reload();
        },
        updated: function(){
        }
    });
</script>

<script>$(function(){$('tbody>tr').flexible_link();});</script>
@endsection

