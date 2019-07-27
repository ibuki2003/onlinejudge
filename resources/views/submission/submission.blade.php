@extends('layouts.page')
@section('title', __('name.submission').' #'.$submission->id)
@section('content')
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
            <tr>
                <th scope="row">{{__("ui.submission.time")}}</th>
                <td>@{{detail.time}}</td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.problemId")}}</th>
                <td><a href="{{route('problem',['id'=>$submission->problem->id])}}">@{{detail.problem.id}} @{{detail.problem.title}}</a></td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.sender")}}</th>
                <td>@{{detail.sender}}</td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.lang")}}</th>
                <td>@{{detail.lang}}</td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.point")}}</th>
                <td>@{{detail.point}}</td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.size")}}</th>
                <td>@{{detail.size}}</td>
            </tr>
            <tr>
                <th scope="row">{{__("ui.submission.exec_time")}}</th>

                <td v-if="detail.exec_time===null">--</td>
                <td v-else>@{{detail.exec_time}}ms</td>
            </tr>
            <tr :class="'table-'+statusColors[detail.status]">
                <th scope="row">{{__("ui.submission.status")}}</th>
                <td>@{{detail.status}}</td>
            </tr>
        </tbody>
    </table>
</div>
@if (auth()->user()->has_permission('admit_users'))
<button type="button" class="btn btn-danger" @click="rejudge">{{__('ui.problem.rejudge')}}</button>
@endif
<hr>
<h2>{{__('ui.submission.source')}}</h2>
<pre><code>@{{ {!! str_replace('}', '\}', e(json_encode($submission->get_source()))) !!} }}</code></pre>

<section v-if="compile_result !== null">
<h2>{{__('ui.submission.compile_result')}}</h2>
<pre><code>@{{compile_result}}</code></pre>
</section>

<section v-if="judge_result !== null">
    <h2>{{__('ui.problem.test_case.testcases')}}</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('ui.problem.test_case.set_name')}}</th>
                    <th>{{__('ui.problem.test_case.point')}}</th>
                    <th>{{__('ui.problem.test_case.testcases')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tcset of judge_result.tcsets">
                    <td>@{{tcset.name}}</td>
                    <td>@{{tcset.got ? tcset.perfect : 0}}/@{{tcset.perfect}}</td>
                    <td>@{{tcset.problems.join()}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('ui.problem.test_case.filename')}}</th>
                    <th>{{__('ui.problem.test_case.status')}}</th>
                    <th>{{__('ui.problem.test_case.exec_time')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="testcase of judge_result.result" :class="'table-'+statusColors[testcase.status]">
                    <th scope="row">@{{testcase.name}}</th>
                    <td :colspan="['AC','WA'].indexOf(testcase.status)>=0 ? 1 : 2">@{{testcase.status}}</td>
                    <td v-if="['AC','WA'].indexOf(testcase.status)>=0">@{{testcase.time}}ms</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('script')
<script>
    const detail_url='/api/submissions/{{$submission->id}}';
    const compile_result_url=detail_url + '/compile_result';
    const judge_result_url=detail_url + '/judge_result';
    const rejudge_url='/submissions/{{$submission->id}}/rejudge';

    new Vue({
        el: 'main',
        data: {
            detail: {
                time: '{{$submission->time}}',
                problem: {
                    id: {{$submission->problem->id}},
                    title: '{{$submission->problem->title}}',
                },
                user_id: '{{$submission->user_id}}',
                lang: '{{$submission->lang->name}}',
                point: {{$submission->point}},
                size: {{$submission->size}},
                exec_time: {{$submission->exec_time ?? '1'}},
                status: '{{$submission->status}}',
            },
            compile_result: null,
            judge_result: null,

            detail_loading: false,
            compile_result_loading: false,
            judge_result_loading: false,

            statusColors: {
                @foreach (config('oj.status_color') as $key=>$data)
                {{$key}}:'{{$data}}',
                @endforeach
            },
        },
        methods: {
            reload: function(){
                new Promise(function (resolve, reject) {
                    if(!this.detail_loading){
                        this.detail_loading=true;
                        $.getJSON(detail_url)
                        .done(function(data){
                            this.detail=data.data;
                        }.bind(this))
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                        }.bind(this))
                        .always(function() {
                            this.detail_loading=false;
                            resolve();
                        }.bind(this));
                    }else{
                        resolve();
                    }
                }.bind(this)).then(function(){
                    if(['WJ', 'WR'].indexOf(this.detail.status)<0){ // judge has already done
                        if(!this.compile_result_loading){
                            this.compile_result_loading=true;
                            $.get(compile_result_url)
                            .done(function(data, responseText, jqXHR){
                                if(jqXHR.status == 204)
                                    this.compile_result=null;
                                else
                                    this.compile_result=data;
                            }.bind(this))
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus);
                            }.bind(this))
                            .always(function() {
                                this.compile_result_loading=false;
                            }.bind(this));
                        }

                        if(!this.judge_result_loading){
                            this.judge_result_loading=true;
                            $.getJSON(judge_result_url)
                            .done(function(data, responseText, jqXHR){
                                if(jqXHR.status == 204)
                                    this.judge_result=null;
                                else
                                    this.judge_result=data;
                            }.bind(this))
                            .fail(function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus);
                            }.bind(this))
                            .always(function() {
                                this.judge_result_loading=false;
                            }.bind(this));
                        }
                    }
                }.bind(this));
            },
            rejudge: function(){
                $.ajax({
                    type: 'POST',
                    url: rejudge_url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(){
                    this.compile_result=null;
                    this.judge_result=null;
                    this.reload();
                }.bind(this));
            }
        },
        created: function(){
            this.reload();
            setInterval(function(){
                if(['WJ','WR'].indexOf(this.detail.status)>=0)this.reload();
            }.bind(this), 1000);
        }
    });
</script>
@endsection
