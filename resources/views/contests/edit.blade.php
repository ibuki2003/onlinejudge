@extends('layouts.page')
@section('title', __('name.contest.edit'))

@section('content')

<div id="problems-controller">
    <div class="form-group">
        <label for="title-input">{{__('ui.contest.title')}}</label>
        <input type="text" id="title-input" name="title" class="form-control" value="{{$contest->title}}" required>
        <div class="invalid-feedback" id="title-error"></div>
    </div>
    <div class="form-group">
        <label for="description-input">{{__('ui.contest.description_optional')}}</label>
        <textarea id="description-input" name="description" class="form-control">{{$contest->description}}</textarea>
        <div class="invalid-feedback" id="description-error"></div>
    </div>
    <div class="form-group">
        <label for="start_time-input">{{__('ui.contest.start_time')}}</label>
        <input type="datetime-local" id="start_time-input" name="start_time" class="form-control" value="{{str_replace(' ', 'T', $contest->start_time)}}">
        <div class="invalid-feedback" id="start_time-error"></div>
    </div>
    <div class="form-group">
        <label for="end_time-input">{{__('ui.contest.end_time')}}</label>
        <input type="datetime-local" id="end_time-input" name="end_time" class="form-control" value="{{str_replace(' ', 'T', $contest->end_time)}}">
        <div class="invalid-feedback" id="end_time-error"></div>
    </div>
    <div class="form-group">
        <label for="penalty-input">{{__('ui.contest.penalty_in_munites')}}</label>
        <input type="number" min="0" max="100000" value="5" id="penalty-input" name="penalty" class="form-control" value="{{$contest->penalty}}">
        <div class="invalid-feedback" id="penalty-error"></div>
    </div>
    <label for="problems">{{__('ui.contest.problems')}}</label>
    <p><span class="text-danger" id="problems-error"></span></p>
    <div class="input-group m-2">
        <select class="form-control" id="problem_new" v-model="problem_new">
            @foreach($problems as $problem)
                <option value="{{$problem->id}}">{{$problem->id.' : '.$problem->title}}</option>
            @endforeach
        </select>
        <div class="input-group-append">
            <button type="button" class="btn btn-secondary" v-on:click="add_problem(1)">{{__('ui.add')}}</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('ui.problem.title')}}</th>
                    <th scope="col">{{__('ui.contest.point_allotted')}}</th>
                    <th scope="col">{{__('ui.contest.remove_problem')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in problems" v-bind:key="row.id">
                    <td>@{{row.id}}</td>
                    <td><a v-bind:href="'/problems/'+row.id">@{{problem_titles[row.id]}}</a></td>
                    <td>
                        <input type="number" class="form-control" min="0" max="3000" id="point_allotted" v-model:value="row.point" onkeyup="if (!checkValidity(this)) $(this).addClass('is-invalid'); else $(this).removeClass('is-invalid');">
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary" v-on:click="remove_problem(row.id)">{{__('ui.contest.remove_problem')}}</button>
                        <button type="button" v-bind:class="{'btn btn-secondary' : index > 0, 'invisible' : index <= 0}" v-on:click="move_up(row.id)">
                            <i class="fas fa-long-arrow-alt-up"></i>
                        </button>
                        <button type="button" v-bind:class="{'btn btn-secondary' : index + 1 < problems.length, 'invisible' : index + 1 == problems.length}" v-on:click="move_down(row.id)">
                            <i class="fas fa-long-arrow-alt-down"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <button type="button" v-on:click="submit()" class="btn btn-primary">{{__('ui.submit')}}</button>
</div>

@endsection

@section('style')
<style>
.progress{min-width:60px;}
</style>
@endsection


@section('script')
<script>
    new Vue({
        el: '#problems-controller',
        data: {
            problems : [ // [i]['id'] : problem id, [i]['point'] : point
                @foreach(explode(',', $contest->problem_ids) as $index => $problem_id)
                    {id : {{$problem_id}}, point : {{explode(',', $contest->problem_points)[$index]}}},
                @endforeach
            ], 
            problem_titles : {
                @foreach ($problems as $problem)
                {{$problem->id}}:'{{$problem->title}}',
                @endforeach
            },
            problem_new : '',
            errors : [],
        },
        methods : {
            add_problem : function() {
                if (!(this.problem_new in this.problem_titles)) return;
                for (var problem of this.problems) {
                    if (problem.id == Number(this.problem_new)) return;
                }
                this.problems.push({id : Number(this.problem_new), point : 100});
            },
            remove_problem : function(id) {
                this.problems = this.problems.filter(problem => problem.id !== id);
            },
            move_up : function(id) {
                moving_index = this.problems.findIndex(function(problem) {
                    return problem.id == id;
                });
                moving_problem = this.problems[moving_index];
                this.problems[moving_index] = this.problems[moving_index - 1];
                Vue.set(this.problems, moving_index - 1, moving_problem);
            },
            move_down : function(id) {
                moving_index = this.problems.findIndex(function(problem) {
                    return problem.id == id;
                });
                moving_problem = this.problems[moving_index];
                this.problems[moving_index] = this.problems[moving_index + 1];
                Vue.set(this.problems, moving_index + 1, moving_problem);
            },
            submit: function(){
                $.ajax({
                    type: "POST",
                    url: '{{route("contest_edit", $contest->id)}}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'problems' : this.problems,
                        'title' : $('#title-input').val(),
                        'description' : $('#description-input').val(),
                        'start_time' : $('#start_time-input').val(),
                        'end_time' : $('#end_time-input').val(),
                        'penalty' : $('#penalty-input').val(),
                    }
                })
                .done(function(){
                    location.href = "/contests/" + {{$contest->id}};
                }.bind(this))
                .fail(function(jqXHR, textStatus, errorThrown){
                    this.errors = $.parseJSON(jqXHR.responseText)['errors'];
                    console.log(this.errors);
                    for (var id of ['title', 'description', 'start_time', 'end_time', 'penalty']) {
                        if (id in this.errors) {
                            $('#' + id + '-input').attr('class', 'form-control is-invalid');
                            $('#' + id + '-error').html('<strong>' + this.errors[id] + '</strong>');
                        } else {
                            $('#' + id + '-input').attr('class', 'form-control');
                            $('#' + id + '-error').html('');
                        }
                    }
                    if ('problems' in this.errors) {
                        $('#problems-error').html('<strong>' + this.errors['problems'] + '</strong>');
                    } else {
                        $('#problems-error').html('');
                    }
                }.bind(this));
            },
            
        },
        updated : function() {
            
        }
    });
</script>
@endsection

