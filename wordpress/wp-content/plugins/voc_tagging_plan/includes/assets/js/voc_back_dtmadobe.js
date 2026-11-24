document.addEventListener('DOMContentLoaded', function () {
    var selectValue = '';
    if (document.querySelector('.ajaxurl')!=null)
        var ajax_url = document.querySelector('.ajaxurl').value;

    if (document.querySelector('.addNew')!=null)
        document.querySelector('.addNew').addEventListener("click", addNewElement);

    if (document.querySelector('.btnguardartags') != null)
        document.querySelector('.btnguardartags').addEventListener("click", saveVariables);

    if (document.querySelector('.actualizarUrl') != null)
        document.querySelector('.actualizarUrl').addEventListener("click", actualizarUrl);

    if (document.querySelectorAll('.btnclosemodal') != null)
        var btncancelar = document.querySelectorAll('.btnclosemodal');
        btncancelar.forEach(value => {
            value.addEventListener("click", function (e) {
                document.querySelector('.primarycontent').innerHTML = '';
            });
        });

    if (document.querySelectorAll('.closecross') != null)
        var btncancelar = document.querySelectorAll('.closecross');
        btncancelar.forEach(value => {
        value.addEventListener("click", function (e) {
            document.querySelector('.primarycontent').innerHTML = '';
        });
    });


    if (document.querySelector('.btnguardarurls') != null)
        document.querySelector('.btnguardarurls').addEventListener("click", saveURLS);

    // loadPatrones(ajax_url).then(res => {
    //     var tablebody = document.querySelector('.tablaurls');
    //     var html = '';
    //     res.forEach(value => {
    //         html += '<tr class="gradeX"><th scope="row">' + value['id'] + '</td><td>' + value['url'] + '</td><td>' + value['patron:'] + '</td><td class="center"><button type="button" class="btn btn-outline-dark btn_create_var" data-toggle="modal" data-target="#newvariables" data-id="' + value['id'] + '" >Añadir Variables</button></td></tr>';
    //     });
    //     tablebody.innerHTML = html;
    //     var elements = document.querySelectorAll('.btn_create_var');
    //     elements.forEach(value => {
    //         value.addEventListener("click", function (e) {
    //             changeEventHandler(e.target.dataset.id);
    //             document.querySelector('.idtotal').value = e.target.dataset.id;
    //         });
    //     });

    // });

    if (document.querySelectorAll('.btn_create_var') != null){
        var elements = document.querySelectorAll('.btn_create_var');
        elements.forEach(value => {
            value.addEventListener("click", function (e) {
                changeEventHandler(e.target.dataset.id);
                document.querySelector('.idtotal').value = e.target.dataset.id;
                document.querySelector('.nomUrl').innerHTML = e.target.dataset.ulr;
            });
        });
    }


    if (document.querySelector('.btnEditar') != null) {
        var elements = document.querySelectorAll('.btnEditar');
        for (var i = 0; i < elements.length; i++) {
            elements[i].addEventListener('click', function (e) {
                document.querySelector('.textUrl').innerHTML = e.target.dataset.url;
                document.querySelector('.idEditUrl').value = e.target.dataset.id;
            });
        }
    }

    if (document.querySelector('.btnBorrar') != null) {
        var elements = document.querySelectorAll('.btnBorrar');
        for (var i = 0; i < elements.length; i++) {
            elements[i].addEventListener('click', function (e) {
                alert('borrar');
            });
        }
    }


}, false);


function addNewElement() {
    var valueVar = document.querySelector('.valueVar').value;
    var valueVal = document.querySelector('.valueVal').value;

    document.querySelector('.valueVar').value = "";
    document.querySelector('.valueVal').value = "";

    var newElement = '<div class="input-group dynamicaldivelement"><div class="input-group-prepend"><span class="input-group-text">Variable | Posición</span></div ><input type="text" class="form-control valuevar" data-valuevar="' + valueVar + '" value="' + valueVar + '"><input type="text" class="form-control valueval" data-valueval="' + valueVal + '" value="' + valueVal + '"><input class="btn btn-danger deleteElement" type="button" value="-"></div>';

    var firstelement = document.querySelector('.primarycontent');
    firstelement.insertAdjacentHTML('beforeend', newElement);

    var elements = document.querySelectorAll('.deleteElement');
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', deleteElement);
    }
}


function saveVariables() {
    var ajax_url = document.querySelector('.ajaxurl').value;


    var obj = {};
    var variables = {};

    var elements = document.querySelectorAll('input.valuevar');
    var elements2 = document.querySelectorAll('input.valueval');


    for (var i = 0; i < elements.length; i++) {
        if (elements[i].getAttribute("data-valuevar") != null) {
            // variables[elements[i].getAttribute("data-valuevar")] = elements[i + 1].getAttribute("data-valueval");
            variables[elements[i].value] = elements2[i].value;
        }
    }

    obj['idpatron'] = document.querySelector('.idtotal').value;
    obj['variables'] = variables;

    elements = document.getElementsByTagName("select")
    for (var i = 0; i < elements.length; i++) {
        elements[i].selectedIndex = 0;
    }

    var dynamicaldivelement = document.querySelectorAll('.dynamicaldivelement');
    for (var i = 0; i < dynamicaldivelement.length; i++) {
        dynamicaldivelement[i].remove();
    }

    save_variables(obj, ajax_url).then(res => {
        console.log(res);
        document.querySelector('.primarycontent').innerHTML = '';
    });
}

function actualizarUrl() {
    var id = document.querySelector('.idEditUrl').value;
    var url = document.querySelector('.valEditUrl').value;
    actualizar_Url(url, id).then(res => {
        console.log(res);
        location.reload();
    });
}


function deleteElement(e) {
    var parentElement = e.srcElement.parentNode;
    parentElement.remove();
}

function changeEventHandler(event) {
    var ajax_url = document.querySelector('.ajaxurl').value;
    get_voc_vars(ajax_url, event).then(res=> {
        console.log(res);
        if(res != 0)
        {
            var output = Object.entries(res.variables).map(([key, value]) => ({ key, value }));
            output.forEach(value => {
                var newElement = '<div class="input-group dynamicaldivelement"><div class="input-group-prepend"><span class="input-group-text">Variable | Posición</span></div ><input type="text" class="form-control valuevar" data-valuevar="' + value.key + '" value="' + value.key + '"><input type="text" class="form-control valueval" data-valueval="' + value.value + '" value="' + value.value + '"><input class="btn btn-danger deleteElement" type="button" value="-"></div>';
                var firstelement = document.querySelector('.primarycontent');
                firstelement.insertAdjacentHTML('beforeend', newElement);

                var elements = document.querySelectorAll('.deleteElement');
                for (var i = 0; i < elements.length; i++) {
                    elements[i].addEventListener('click', deleteElement);
                }
            })
        }else{
            document.querySelector('.primarycontent').innerHTML = '';
        }
    });
}

function saveURLS() {
    var ajax_url = document.querySelector('.ajaxurl').value;
    var url = document.querySelector('#taggin-url').value;
    var patron = document.querySelector('#regular-expresion').value;
    var usarpatron = document.querySelector('.usarpatron').checked;
    obj = {
        'id': '',
        'url': url,
        'patron:': patron,
        'usarpatron': usarpatron
    };

    save_url(obj, ajax_url).then(res => {
        document.querySelector('#taggin-url').value = "";
        document.querySelector('#regular-expresion').value = "";
        location.reload();
    });
}

/**
 * consultas ajax
 */
var save_variables = (obj, ajax_url) => new Promise((resolve, reject) => {
    var data = {
        'action': 'save_variables',
        'post_type': 'post',
        'obj': obj,
    };

    jQuery.post(ajax_url, data, function (response) {
        resolve(response);
    }, 'json');
});

var save_url = (obj, ajax_url) => new Promise( (resolve, reject) => {
    var data = {
        'action': 'save_url',
        'post_type': 'post',
        'obj': obj
    }
    jQuery.post(ajax_url, data, function(response) {
        resolve(response);
    },'json');
});

var loadPatrones = (ajax_url) => new Promise( (resolve, reject) => {
    var data = {
        'action': 'get_urls',
        'post_type': 'post'
    }
    jQuery.post(ajax_url, data, function(response) {
        resolve(response);
    },'json');

});

var get_voc_vars = (ajax_url, idurl) => new Promise ( (resolve, reject) => {
    var data = {
        'action': 'get_voc_vars',
        'id': idurl,
        'post_type': 'post'
    }
    jQuery.post(ajax_url, data, function(response) {
        resolve(response);
    },'json');
});

var actualizar_Url = (url, id) => new Promise ( (resolve, reject) => {
    var ajax_url = document.querySelector('.ajaxurl').value;
    var data = {
        'action': 'updateUrl',
        'id': id,
        'url': url
    }
    jQuery.post(ajax_url, data, function (response) {
        resolve(response);
    }, 'json');
});
