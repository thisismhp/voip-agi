<template>
    <div class="modal fade" id="message-modal" tabindex="-1" role="dialog" aria-labelledby="myMessage" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div :class="`modal-header text-${mode}`">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span style="font-size: 4vh;" aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLabel">{{title}}</h5>
                </div>
                <div class="modal-body">
                    {{content}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DialogMessage",

        props:{
            show:{
                type: Boolean,
                required: true,
            },
            title:{
                type:String,
                required: true
            },
            content:{
                type:String,
                required: true
            },
            mode:{
                type:String,
                required: true
            },
            showTime:{
                type:Number,
                required:true,
            }
        },
        methods:{
            async showDialog() {
                const element = $('#message-modal');
                element.modal('show');
                if (this.showTime != null) {
                    await this.sleep(this.showTime);
                    element.modal('hide');
                }
            },
            sleep(ms){
                return new Promise(resolve => setTimeout(resolve, ms));
            },
        },
        watch:{
            show: function (value) {
                if(value === true){
                    this.showDialog();
                    this.$emit('show')
                }
            }
        }
    }
</script>

<style scoped>

</style>