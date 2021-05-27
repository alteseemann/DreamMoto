<template>
    <div class="d-flex flex-column" style="width: max-content">
        <h4 class="title mt-2 mb-3" >Изменить местоположение</h4>
        <div style="width: max-content; max-width: 600px">
            <p>Переставьте маркер в новое место</p>
        </div>

        <!-- Яндекс.Карты -->
        <div class="row m-0 p-0 justify-content-center" style="width: max-content">
            <yandex-map
                :controls="['typeSelector', 'zoomControl','fullscreenControl']"
                :coords="coords"
                @click="setMark"
                zoom="17"
                style="width: 600px; height: 400px; border: 1px solid black"
            >

                <ymap-marker
                    :coords="coords"
                    marker-id="1"
                    hint-content="some hint"
                />

            </yandex-map>
        </div>
        <!-- Яндекс.Карты -->

        <form @submit.prevent="send" method="post">

            <input type="hidden" name="_token" v-model="token"/>
            <input type="hidden" name="coords" v-model="coords"/>

            <div class="d-flex">
                <div class="w-100">
                    <h4 class="title mt-2 mb-3" >Изменение контактных данных</h4>
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="organization" class="pl-0 text-left col-sm-2 col-form-label">Организация</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="organization" v-model="organization" :placeholder=dealer.title>
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="city" class="pl-0 text-left col-sm-2 col-form-label">Город</label>
                <div class="col-sm-10">
                    <input
                        type="text"
                        class="form-control custom_placeholder"
                        id="city"
                        v-model="city"
                        v-on:input="filter_city(city)"
                        :placeholder="placeholder_city"
                    ><!--При вводе с клавиатуры каждый раз будут фильтроваться города в соответствии с текущим значением this.city-->
                    <!--Выпадающее меню, которое откроется, если поле "Город" не пустое-->
                    <div v-if="city !== dealer.city.title && close_dropdown === 0" class="city_dropdown text-left w-50 overflow-auto">
                        <ul class="city_list p-0">
                            <li
                                v-for="cur_city in current_cities"
                                class="city"
                                v-on:click="set_city(cur_city)"
                            >
                                {{cur_city}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="form-group row m-0 p-0">
                <label for="adress" class="pl-0 text-left col-sm-2 col-form-label">Адрес</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="adress" v-model="adress" :placeholder=dealer.address>
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="phone" class="pl-0 text-left col-sm-2 col-form-label">Телефон</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" v-model="phone" :placeholder=dealer.phone>
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="site" class="pl-0 text-left col-sm-2 col-form-label">Веб-сайт</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="site" v-model="site" :placeholder=dealer.site>
                </div>
            </div>

            <div class="d-flex">
                <div class="w-100">
                    <h4 class="title mt-2 mb-3" >Какую технику вы продаете?</h4>
                </div>
            </div>

            <div class="custom-control custom-checkbox text-left" v-for="moto in motos">
                <input type="checkbox" class="custom-control-input" :id="moto.id" v-on:click="findBrands(moto.id)">
                <label class="custom-control-label" :for="moto.id">{{moto.title}}</label>
                <div v-if="moto_types.includes(moto.id)"
                     class="d-flex flex-wrap justify-content-start"
                     style="width: 600px;">
                    <div class="custom-control custom-checkbox"
                         v-for="brand in brands[moto.id]"
                         style="width: 150px">
                        <input type="checkbox"
                               class="custom-control-input"
                               :id="moto.id+'_'+brand.id"
                               v-on:click="set_checked(moto.id,brand.id)">
                        <label class="custom-control-label" :for="moto.id+'_'+brand.id">{{brand.title}}</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-2">Готово</button>

        </form>

        <!--Вывод ошибок-->
        <Popup title='Обнаружены ошибки!' custom_style="bg-danger" id="errors">
            <div v-for="error in errors">{{error}}</div>
        </Popup>
        <!--Вывод ошибок-->

        <!--Вывод сообщения "Успешно"-->
        <Popup title='Данные успешно добавлены.' custom_style="bg-success" id="success">
            <div class="text-bold">{{message}}</div>
        </Popup>
        <!--Вывод сообщения "Успешно"-->

    </div>
</template>

<script>
    import Popup from "../Modal/Popup";
    export default {
        name: "EditSettings",
        props:['motos','dealer'],
        components: {Popup,},
        data() {
            return {
                token:'',
                organization:this.dealer.title,
                cities:{},//список вообще всех городов, который передается из контроллера в момент загрузки страницы
                current_cities:[],//отфильтрованный в соответствии с текущим значением инпута массив городов
                city:this.dealer.city.title,//значение, привязанное к инпуту city
                placeholder_city:this.dealer.city.title,//отображается в качестве плейсхолдера инпута city
                close_dropdown:0,
                adress:this.dealer.address,
                phone:this.dealer.phone,
                site:this.dealer.site,
                brands:{},
                moto_types:[],//список типов техники, которые выбрал пользователь
                moto_brands:[],//конечный массив вида [motoID_brandID]
                coords: [//Координаты в начальный момент отображения на экране
                    this.dealer.latitude,
                    this.dealer.longitude
                ],
                initial_coords: [//Координаты в начальный момент отображения на экране
                    this.dealer.latitude,
                    this.dealer.longitude
                ],
                errors:[],//ошибки, валидацию формы сделаю на фронте
                message:'',
                data:{},

            }
        },
        mounted() {
            //console.log(this.dealer)
            this.token = window.csrf_token
            let options = {
                motos:this.motos
            }
            axios//получаем список брендов для каждого типа техники а также список городов
                .post('/home/personal/get_brands',options)
                .then(response => {
                    //формируем массив вида [moto_id=>[brand_1,brand_2,...brand_n]]
                    this.brands = response.data.data.brand_list
                    this.cities = response.data.data.cities
                    //console.log(response.data.data.cities)
                })
                .catch(error=>{
                    console.log(error)
                })
        },
        methods: {
            findBrands(id){
                if (!this.moto_types.includes(id)){
                    this.moto_types.push(id)
                }else{
                    this.moto_types.splice(this.moto_types.indexOf(id),1)
                    for (let brand of this.moto_brands){
                        if (brand.split('_')[0]===String(id)){
                            delete this.moto_brands[this.moto_brands.indexOf(brand)]
                        }
                    }
                    this.moto_brands=this.moto_brands.filter(brand => brand != null);
                }
            },

            set_checked(m_id,b_id){
                let m_b = m_id+'_'+b_id
                if (!this.moto_brands.includes(m_b)){
                    this.moto_brands.push(m_b)
                }else{
                    this.moto_brands.splice(this.moto_brands.indexOf(m_b),1)
                }
            },

            //Установка маркера на карту, геокодер
            setMark(e){
                this.coords=e.get('coords')
            },

            //Транслитерация - формируем алиас для дилера
            translit(org){
                let str = org
                let space = ''
                let link = ''
                let transl = {
                    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
                    'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                    'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
                    'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': '',
                    'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
                }
                if (str !== '')
                    str = str.toLowerCase()

                for (var i = 0; i < str.length; i++){
                    if (/[а-яё]/.test(str.charAt(i))){ // заменяем символы на русском
                        link += transl[str.charAt(i)]
                    } else if (/[a-z0-9]/.test(str.charAt(i))){ // символы на анг. оставляем как есть
                        link += str.charAt(i)
                    } else if (/[ ]/.test(str.charAt(i))){ // меняем пробел на подчеркивание
                        link += '_'
                    } else {
                        if (link.slice(-1) !== space) link += space // прочие символы заменяем на space
                    }
                }
                return link
            },

            filter_city(user_city){//Возвращает массив городов, в названии которых есть комбинация введенных символов
                this.current_cities = []//обновляет выпадающий список
                this.close_dropdown = 0//условие отображения списка
                let reg = new RegExp(user_city.toLowerCase())//шаблон поиска
                for (let city of this.cities){
                    if (city.title.toLowerCase().match(reg)){
                        this.current_cities.push(city.title)
                    }
                }
            },

            set_city(current_city){//срабатывает при выборе города в выпадающем меню
                this.city = current_city
                this.close_dropdown=1
                this.placeholder_city = current_city
            },

            //Валидатор
            validator(key,val,par){//Если введенные пользователем данные отличаются от указанных при регистрации
                //то эти данные добавляются в массив измененных параметров
                if (key !== val){
                    this.data[par] = key
                }
            },

            send(){
                //Валидация
                let validator = this.validator
                this.errors=[]
                if(this.coords[0]!==this.initial_coords[0] && this.coords[1]!==this.initial_coords[1]){
                    this.data['coords'] = this.coords
                }
                if(this.moto_brands.length!==0){
                    this.data['moto_brands'] = this.moto_brands
                }
                validator(this.organization,this.dealer.title,'title')
                validator(this.translit(this.organization),this.dealer.alias,'alias')
                validator(this.city,this.dealer.city.title,'city')
                validator(this.adress,this.dealer.address,'address')
                validator(this.phone,this.dealer.phone,'phone')
                validator(this.site,this.dealer.site,'site')
                //console.log(this.data)
                //Запрос
                let request_data ={}
                request_data['changed_data']=this.data
                axios
                    .post('/home/personal/edit_dealer',request_data)
                    .then(response => {
                        this.message = response.data.message
                        $('#success').modal() // Открыть сообщение об успешном добавлении данных
                        console.log(response)
                    })
                    .catch(error=>{
                        this.errors.push('Ошибка сервера: '+error)
                        $('#errors').modal() // Открыть сообщение об ошибках
                        console.log(error)
                    })
            }


        }
    }
</script>

<style scoped>
    .title{
        width: max-content;
        max-width: 600px;
        text-align: left;
    }
    .nec{
        color: red;
    }
    .custom_placeholder::placeholder {color: #000000;}
    .city_dropdown {
        z-index: 1;
        background: white;
        max-height: 200px;
        height: max-content;
        border: 1px solid black;
        position: absolute;
    }
    .city:hover {
        background: #00cee0;
        cursor: pointer;
    }
</style>

