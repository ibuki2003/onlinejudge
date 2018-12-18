<template>
    <ul class="pagination" role="navigation">
        <li class="page-item" v-bind:class="{'disabled':this.current==1}" v-bind:aria-disabled="this.current==1" aria-label="@lang('pagination.previous')">
            <button class="page-link btn btn-link" v-bind:disabled="this.current==1" v-on:click="current--" rel="prev">
                &lsaquo;
            </button>
        </li>
        <li v-bind:class="{'page-item':true,' active':i==current}" v-for="i in page_links" v-bind:key="i">
            <button class="page-link btn btn-link" v-on:click="current=i" v-if="i!=current">{{ i }}</button>
            <span class="page-link" v-else>{{i}}</span>
        </li>
        <li class="page-item" v-bind:class="{'disabled':current==this.last}" v-bind:aria-disabled="current==this.last" aria-label="@lang('pagination.next')">
            <button class="page-link btn btn-link" v-bind:disabled="current==this.last" v-on:click="current++" rel="next">
                &rsaquo;
            </button>
        </li>
    </ul>
</template>
<script>
export default {
    data: function(){
        return {
            current: 1,
        };
    },
    updated: function(){
        this.$emit('input', this.current);
    },
    mounted: function() {
        this.current = this.value;
    },
    props: {
        value: {
            type: Number,
            required: true
        },
        last: {
            type: Number,
            required: true
        }
    },
    computed: {
        page_links: function(){
            if(this.last==1)
                return [1];
            
            var pages=[];

            for(var i=0;this.current-(1<<i)>1;i++){
                pages.unshift(this.current-(1<<i));
            }
            pages.unshift(1);
            if(this.current>1)
                pages.push(this.current);
            for(var i=0;this.current+(1<<i)<this.last;i++){
                pages.push(this.current+(1<<i));
            }
            if(this.last>this.current)
                pages.push(this.last);
            console.log(pages);
            return pages;
        }
    }
}
</script>
