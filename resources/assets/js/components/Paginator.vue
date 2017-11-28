<template>
    <div v-if="active">
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous" v-if="prevUrl" @click.prevent="page--">
                    <span aria-hidden="true">&laquo; Prev</span>
                </a>
            </li>

            <li>
                <a href="#" aria-label="Next" v-if="nextUrl" @click.prevent="page++">
                    <span aria-hidden="true">&raquo; Next</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['dataSet'],
        data(){
            return {
                prevUrl: false,
                nextUrl: false,
                total: 0,
                page: 1,
            }
        },
        computed:{
          active(){
              return this.prevUrl && this.prevUrl;
          }
        },
        watch:{
            dataSet() {
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
                this.page = this.dataSet.current_page;
                this.total = this.dataSet.total;
            },
            page(){
                this.broadcast()
                    .updateUrl();
            }
        },
        methods:{
            broadcast(){
                return this.$emit('changed', this.page);
            },
            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>