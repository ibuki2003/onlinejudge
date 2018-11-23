@extends('layouts.base')
@section('title',__('name.md_editor'))
@section('main')
<main class="h-100 my-4 py-4 bg-white shadow-sm rounded d-flex" id="mdeditor">
    <div class="col m-3 p-0 border">
        <textarea class="w-100 h-100 p-0 m-0" v-model="mdtext"></textarea>
    </div>
    <div class="col m-3 border" id="output" v-html='renderMD'></div>
</main>
@endsection

@section('style')
<style>
#editor{
    resize: none;
}
html,body,main{
    height:100%;
}
</style>
<link rel="stylesheet" href="{{asset('katex/katex.min.css')}}">
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.5.1/marked.min.js"></script>
<script src="{{asset('katex/katex.min.js')}}"></script>
<script src="{{asset('katex/contrib/auto-render.min.js')}}"></script>
<script src="{{asset('js/mdparse.js')}}"></script>
<script>
    new Vue({
        el: '#mdeditor',
        data: {
            mdtext: '',
        },
        computed: {
            renderMD: function () {
                var html=mdoverride(this.mdtext);
                return html.replace(/\$(.+?)\$/g, function (match, p1){
                    console.log(typeof p1);
                    return katex.renderToString(p1);
                });
            }
        },
    });
</script>
@endsection
