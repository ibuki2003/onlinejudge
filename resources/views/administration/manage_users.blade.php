@extends('layouts.page')
@section('title', __('name.manage_users'))
@section('content')
<div id="users-form">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">{{__('ui.user.permission')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="[id,user] in Object.entries(users_modified)" v-bind:class="{'table-info':user.permission!=users[id].permission}">
                    <th scope="row">@{{id}}</th>
                    <td>
                        <select class="form-control" v-model:value="user.permission" v-bind:disabled="id=='{{auth()->id()}}'">
                            <option value="0">Guest</option>
                            <option value="1">Submitter</option>
                            <option value="3">ProblemCreator</option>
                            <option value="7">ContestOwner</option>
                            <option value="15">Administrator</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <button class="btn btn-primary" v-on:click="load_status=0" data-toggle="modal" data-target="#confirm-modal">{{__('ui.submit')}}</button>

    <div class="modal" tabindex="-1" role="dialog" id="confirm-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('name.manage_users')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('ui.admit.confirm')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('ui.cancel')}}</button>
                    <button type="button" class="btn btn-danger" v-on:click="apply">{{__('ui.submit')}}</button>
                    <i v-bind:class="['','loading','error','done'][load_status]"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('css/loadicon.css')}}">
@endsection

@section('script')
<script>
function postForm(url, data) {
    var $form = $('<form/>', {'action': url, 'method': 'post'});
    for(var key in data) {
            $form.append($('<input/>', {'type': 'hidden', 'name': key, 'value': data[key]}));
    }
    $form.appendTo(document.body);
    $form.submit();
}
new Vue({
    el: '#users-form',
    data: {
        users: {},
        users_modified: {},
        load_status: 0,
    },
    created: function(){
        this.users={
            @foreach($users as $user)
                '{{$user->id}}': {
                    'permission':{{$user->permission}},
                },
            @endforeach
        };
        this.users_modified = JSON.parse(JSON.stringify(this.users));
    },
    methods: {
        apply: function(){
            var post_data = {};
            for (id in this.users_modified) {
                for(column of ['permission']){
                    if(this.users[id][column]!=this.users_modified[id][column]){ // If modified
                        if(post_data[id]===undefined)
                            post_data[id] = {};
                        post_data[id][column] = this.users_modified[id][column];
                    }
                }
            }
            this.load_status=1;
            $.ajax({
                type: "POST",
                url: '{{route("manage_users")}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'users_data':post_data
                }
            })
            .done(function(){
                this.load_status=3;
                $('#confirm-modal').modal('hide');
            }.bind(this))
            .fail(function(jqXHR, textStatus, errorThrown){
                console.error(textStatus);
                this.load_status=2;
                setTimeout(function(){
                    $('#confirm-modal').modal('hide')
                },2000);
            }.bind(this));
        }
    }
});
</script>
@endsection
