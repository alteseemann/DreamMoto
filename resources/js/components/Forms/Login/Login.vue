<template>
    <li class="nav-item">
        <a class="nav-link nav-link-top nav-link-top-normal user-login-link"
           data-toggle="modal"
           data-target="#loginModal"
           @click="open_modal=true"
           href="javascript:void(0);">Войти</a>

        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLongTitle"
             aria-hidden="true">
            <div class="modal-dialog" v-if="open_modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="LoginModalLongTitle">Вход</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="pb-form" @submit.prevent>

                        <div class="modal-body">

                            <!--                    {!! Form::open( ['route' => 'login', 'method' => 'POST','class' => 'form-horizontal', 'role' => 'form']) !!}-->

                            <div class="form-group">
                                <label for="email" class="control-label">E-Mail</label>
                                <input id="email" type="email" class="form-control" name="email"
                                       required autofocus
                                       v-model="email">

                            </div>

                            <div class="form-group">
                                <label for="password" class="control-label">Пароль</label>

                                <input id="password" type="password" class="form-control" name="password" required
                                       v-model="password">

                            </div>

                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="remember"
                                       name="remember">
                                <label class="custom-control-label" for="remember">Запомнить меня</label>
                            </div>

                            <!--                        РЕГИСТРАЦИЯ-->
                            <!--                        <a class="nav-link nav-link-top"-->
                            <!--                           onclick="$('#loginModal').modal('hide');"-->
                            <!--                           href="javascript:void(0);"-->
                            <!--                           data-toggle="modal"-->
                            <!--                           data-target="#registerModal">Регистрация-->
                            <!--                        </a>-->

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary"
                                    @click="send()">
                                Войти
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </li>

</template>


<script>
    export default {
        name      : 'login-form',
        props     : {},
        data() {
            return {
                password: '',
                email   : '',
                open_modal: false
            }
        },
        mounted() {
            let self = this;
            $("#loginModal").on('hidden.bs.modal', function(event){
                self.open_modal = false;
            });
        },
        components: {},
        watch     : {},
        methods   : {
            send() {
                let self = this;

                axios.post('/login', {
                    email   : self.email,
                    password: self.password,
                })
                    .then(function (response) {

                        if (response.data.success) {

                            $("#CallBack").modal("hide");
                            location.reload();

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
