<template>
    <div class="call-back">
        <a class="message-link"
           data-toggle="modal"
           data-target="#CallBack"
           @click="open_modal=true"
           href="javascript:void(0);">Обратная связь</a>

        <div class="modal fade" id="CallBack" tabindex="-1" role="dialog" aria-labelledby="CallBackTitle"
             aria-hidden="true">
            <div class="modal-dialog" v-if="open_modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CallBackTitle">Обратная связь</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="pb-form" @submit.prevent>

                        <div class="modal-body">

                            <div class="form-group">
                                <label for="name-callback" class="control-label">Ваше имя</label>
                                <input id="name-callback" type="text" class="form-control" name="email"
                                       autofocus
                                       v-model="name">

                            </div>

                            <div class="form-group">
                                <label for="email-callback" class="control-label">E-Mail</label>
                                <input id="email-callback" type="email" class="form-control" name="email"
                                       required
                                       v-model="email">

                            </div>

                            <div class="form-group">
                                <label for="message" class="control-label">Сообщение</label>

                                <textarea rows="8" id="message" class="form-control" name="password" required
                                          v-model="message">
                                </textarea>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary"
                                    @click="send()">
                                Отправить
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</template>


<script>
    export default {
        name      : 'callback',
        props     : {},
        data() {
            return {
                name   : '',
                email  : '',
                message: '',
                open_modal: false
            }
        },
        mounted() {
            let self = this;
            $("#CallBack").on('hidden.bs.modal', function(event){
                self.open_modal = false;
            });
        },
        components: {},
        watch     : {},
        methods   : {
            send() {
                let self = this;

                axios.post('/callback', {
                    email  : self.email,
                    name   : self.name,
                    message: self.message,
                })
                    .then(function (response) {

                        if (response.data.success) {

                            toastr.success('Сообщение отправлено');
                            self.email   = '';
                            self.name    = '';
                            self.message = '';
                            $("#CallBack").modal("hide");
                            console.log('s');

                        } else {

                            toastr.warning(response.data.error);

                        }
                    })
                    .catch(function (error) {

                    });
            },
        },
    }
</script>
