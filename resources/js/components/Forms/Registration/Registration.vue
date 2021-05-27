<template>
    <div class="pb-modal modal fade"
         tabindex="-1"
         role="dialog"
         aria-labelledby="reg_modal"
         aria-hidden="true"
         id="reg_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content bl-border">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3>Регистрация</h3>
                                <button type="button" class="close" data-dismiss="modal" v-html="icon__close"></button>
                            </div>

                            <div class="panel-body">

                                <form class="pb-form" @submit.prevent>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <input_component :input_options="inputs.name"
                                                             :check_errors="check_errors"></input_component>
                                        </div>
                                        <div class="col-md-6">
                                            <input_component :input_options="inputs.email"
                                                             :check_errors="check_errors"></input_component>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input_component :input_options="inputs.password"
                                                             :check_errors="check_errors"></input_component>
                                        </div>

                                        <div class="col-md-6">
                                            <input_component :input_options="inputs.password_2"
                                                             :check_errors="check_errors"></input_component>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <checkbox_component :input_options="inputs.check"
                                                                :check_errors="check_errors"></checkbox_component>
                                        </div>

                                        <div class="col-md-12">
                                            <checkbox_component :input_options="inputs.subscribe"
                                                                :check_errors="check_errors"></checkbox_component>
                                        </div>
                                    </div>


                                    <div class="form-group js-input-animate-wrap" style="padding: 0">
                                        <small class="form-error-block"
                                               :class="server_error ? ' active ' : ''"
                                               :title="server_error_msg">
                                            {{server_error_msg}}
                                        </small>
                                    </div>

                                    <div class="clearfix">

                                        <button class="reg-link btn btn-link btn-sm pull-left"
                                                type="button"
                                                data-dismiss="modal"
                                                data-toggle="modal"
                                                data-target="#login_modal">
                                            Войти
                                        </button>

                                        <button class="forgot-link btn btn-link btn-sm pull-right"
                                                type="button"
                                                data-dismiss="modal"
                                                data-toggle="modal"
                                                data-target="#forgot_password_modal">
                                            Забыли пароль?
                                        </button>

                                    </div>

                                    <div class="modal-button-wrapper" style="padding-top: 10px;">
                                        <button class="btn btn-main btn-block"
                                                @click="send()">
                                            Зарегистрироваться
                                            <i class="fa fa-spinner fa-spin" v-if="loading"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import VeeValidate, {Validator} from 'vee-validate';

    const dict = {
        custom: {
            reg_form__password: {
                'min': 'Минимум 6 символов'
            },
            reg_form__password_2: {
                'min': 'Минимум 6 символов'
            }
        }
    };
    Validator.localize('ru', dict);

    export default {
        name      : 'reg_form',
        props     : {
            icon__close: {},
            csrf       : {}
        },
        data() {
            return {
                check_errors: false,
                has_errors  : false,

                inputs: {

                    name      : {
                        rules      : '',   //
                        label      : 'Имя',   //
                        vv_as      : 'Имя',   //
                        placeholder: 'Имя',   //
                        type       : 'text',    //
                        id         : 'reg_form_name',  //
                        vmodel     : '',     //
                        name       : 'reg_form_name', //
                    },
                    surname   : {
                        rules      : 'required',
                        label      : 'Фамилия',
                        vv_as      : 'Фамилия',
                        placeholder: 'Фамилия*',
                        type       : 'text',
                        id         : 'reg_form_surname',
                        vmodel     : '',
                        name       : 'reg_form_surname',
                    },
                    patronymic: {
                        rules      : '',
                        label      : 'Отчество',
                        vv_as      : 'Отчество',
                        placeholder: 'Отчество',
                        type       : 'text',
                        id         : 'reg_form_patronymic',
                        vmodel     : '',
                        name       : 'reg_form_patronymic',
                    },

                    email: {
                        rules      : 'required',   //
                        label      : 'E-mail',   //
                        vv_as      : 'E-mail',   //
                        placeholder: 'E-mail*',   //
                        type       : 'text',    //
                        id         : 'reg_form__email',  //
                        vmodel     : '',     //
                        name       : 'reg_form__email', //
                    },
                    phone: {
                        rules      : '',   //
                        label      : 'Телефон',   //
                        vv_as      : 'Телефон',   //
                        placeholder: 'Телефон',   //
                        type       : 'text',    //
                        id         : 'reg_form__phone',  //
                        vmodel     : '',     //
                        name       : 'reg_form__phone', //
                    },

                    password  : {
                        rules       : 'required|min:6',   //
                        label       : 'Пароль',   //
                        vv_as       : 'Пароль',   //
                        placeholder : 'Пароль*',   //
                        type        : 'password',    //
                        id          : 'reg_form__password',  //
                        vmodel      : '',     //
                        name        : 'reg_form__password', //
                        autocomplete: 'new-password', //
                    },
                    password_2: {
                        rules       : 'required|min:6',   //
                        label       : 'Подтверждение пароля',   //
                        vv_as       : 'Подтверждение пароля',   //
                        placeholder : 'Подтверждение пароля*',   //
                        type        : 'password',    //
                        id          : 'reg_form__password_2',  //
                        vmodel      : '',     //
                        name        : 'reg_form__password_2', //
                        autocomplete: 'new-password', //
                    },

                    check: {
                        rules : 'required',
                        label : 'Я даю согласие на обработку персональных данных в соответствии с <a href="/privacy_policy" target="_blank"> политикой конфенденциальности </a> компании Парижанка',
                        vv_as : 'Согласие на обработку',
                        id    : 'reg_form__check',
                        vmodel: '1',
                        name  : 'reg_form__check',
                    },

                    subscribe: {
                        rules : '',
                        label : 'Подписка на новости',
                        vv_as : 'Подписка на новости',
                        id    : 'reg_form__subscribe',
                        vmodel: '',
                        name  : 'reg_form__subscribe',
                    },

                },

                show_inf        : true,
                show_errors     : false,
                server_error    : false,
                server_error_msg: '',
                loading         : false,
            }
        },

        mounted() {
            let self = this;
        },
        components: {},
        watch     : {

            'inputs.password_2.vmodel': {
                handler: function (val, oldVal) {
                    let self = this;
                    let pass = self.inputs.password.vmodel;

                    if (pass.length) {

                        self.check_equal_pass(pass, val);
                    }
                },
                deep   : true
            },

            'inputs'   : function (val) {
                let self          = this;
                self.server_error = false;
            },
        },
        methods   : {
            check_equal_pass(pass, pass_2) {
                let self          = this;

                if (pass !== pass_2) {
                    self.server_error     = true;
                    self.server_error_msg = 'Пароли не совпадают';
                    return false;

                }else {
                    self.server_error     = false;
                    self.server_error_msg = '';
                    return true;
                }
            },
            send() {
                let self        = this;
                self.has_errors = false;

                self.$children.forEach(child => {
                    if (child.errors.items.length) {
                        self.check_errors = Math.random();
                        self.has_errors   = true;
                        return;
                    }
                });

                if (self.has_errors) {
                    return;
                }

                if (!self.check_equal_pass(self.inputs.password.vmodel, self.inputs.password_2.vmodel)) {
                    return;
                }

                self.loading = true;

                axios.post('/register', {
                    name      : self.inputs.name.vmodel,
                    surname   : self.inputs.surname.vmodel,
                    patronymic: self.inputs.patronymic.vmodel,
                    email     : self.inputs.email.vmodel,
                    phone     : self.inputs.phone.vmodel,
                    password  : self.inputs.password.vmodel,
                    password_2: self.inputs.password_2.vmodel,
                    subscribe : self.inputs.subscribe.vmodel,
                })
                    .then(function (response) {
                        self.loading = false;

                        if (response.data.success) {

                            toastr.success(response.data.msg);

                            location.reload();
                        }

                        if (response.data.error) {

                            toastr.warning(response.data.msg, 'Ошибка');
                            self.server_error_msg = response.data.msg;

                            self.server_error = true;

                            setTimeout(function () {
                                self.server_error = false;
                            }, 3000);
                        }
                    })
                    .catch(function (error) {
                        self.loading = false;
                        if (error.response.status == 422) {
                            self.server_error = true;
                        }
                    });
            },
        },
    }
</script>
