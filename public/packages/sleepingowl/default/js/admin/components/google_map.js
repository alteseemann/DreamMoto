function initGoogleMap() {
    console.log('Map init');
    $('body').trigger('googleMapInit')
}

$(document).ready(function () {
    //Карта
    (function () {

        let center_point = {lat : parseFloat(53.390725), lng : parseFloat(49.472270)};
        window.admin_map = new google.maps.Map(document.getElementById('google_map'), {
            zoom   : 9,
            center : center_point
        });

    }());

});

function geocodeAddress() {

    let geocoder = new google.maps.Geocoder();
    let geocode_address = $('#geocode_address');
    let geocode_latitude = $('#geocode_latitude');
    let geocode_longitude = $('#geocode_longitude');

    geocoder.geocode({'address' : geocode_address.val()}, function (results, status) {
        if (status === 'OK') {

            geocode_latitude.html(results[0].geometry.location.lat());
            geocode_longitude.html(results[0].geometry.location.lng());

            let center_point = {
                lat : results[0].geometry.location.lat(),
                lng : results[0].geometry.location.lng()
            };

            window.admin_map = new google.maps.Map(document.getElementById('google_map'), {
                zoom   : 9,
                center : center_point
            });

            let marker = new google.maps.Marker({
                map      : window.admin_map,
                position : results[0].geometry.location
            });
        } else {
            alert('Не удалось определить координаты: ' + status);
        }
    });
}
