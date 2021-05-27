@extends('layouts.app')

@section('content')
    <h1 class="font-black font-bold mt-3 mb-3">Настройки</h1>
    <div class="d-flex flex-nowrap">

        <div class="w-25" id="side-bar">
            <ul class="list5a">
                <li class="font-weight-bold text-uppercase" onclick="ShowContacts('new')">Мои контакты</li>
                <li class="font-weight-bold text-uppercase" onclick="ShowContacts('edit')">Редактировать профиль</li>
            </ul>
        </div>

        <div class="w-75 text-center" id="components">
            <div id="greeting" class="greeting">
                @if(session('not_dealer'))
                    <div class="alert alert-danger">
                        {{session('not_dealer')}}
                    </div>
                @elseif($is_registred == 1)
                    <div>Вы уже ввели свои данные и можете редактировать их в настройках профиля</div>
                @endif
            </div>
            <div class="row justify-content-center m-0 p-0">
                @if($is_registred!=1)
                <div id="my_contacts" class="my_contacts_hidden">
                    <personal-settings v-bind:motos="{{$motos}}"></personal-settings>
                </div>
                @elseif($is_registred == 1)
                <div id="edit_contacts" class="my_contacts_hidden">
                    <edit-settings v-bind:motos="{{$motos}}" v-bind:dealer="{{$dealer_data}}"></edit-settings>
                </div>
                @endif
            </div>
        </div>

    </div>
    <script type="application/javascript">
        var ShowContacts = (type) => {
            if (type === 'new'){
                contacts = document.getElementById('my_contacts')
            }
            if (type === 'edit'){
                contacts = document.getElementById('edit_contacts')
            }
            if (contacts === null){
                location.reload()
            }
            let greeting = document.getElementById('greeting')
            if (contacts.className === 'my_contacts_hidden'){
                contacts.className = 'my_contacts'
                greeting.className = 'greeting_hidden'
            }else{
                contacts.className = 'my_contacts_hidden'
                greeting.className = 'greeting'
            }
        }
    </script>
@endsection
