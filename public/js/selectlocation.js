let locationId = [];
let client = [];
const inputs = document.getElementsByTagName('input');
const btnCreate = document.getElementById("btnCreate");
const area = document.getElementById("area");
const city = document.getElementById("city");
const dataId = document.getElementById("location_id");
const category = document.getElementById("category");
const type = document.getElementById("type");
const requestService = document.getElementById("requestService");
const requestType = document.getElementById("requestType");
if(category.value == "Service"){
    var getType = "existing";
}else{
    var getType = "new";
}

if(requestService.value){
    getType = requestService.value;
    if (requestService.value == "new") {
        document.getElementById("newRadioService").checked = true;
        document.getElementById("existingRadioService").checked = false;
    } else if (requestService.value == "exisiting") {
        document.getElementById("newRadioService").checked = false;
        document.getElementById("existingRadioService").checked = true;
    }
}

if(requestType.value){
    getType = requestType.value;
    if (requestType.value == "new") {
        document.getElementById("newType").checked = true;
        document.getElementById("extendType").checked = false;
    } else if (requestType.value == "extend") {
        document.getElementById("newType").checked = false;
        document.getElementById("extendType").checked = true;
    }
}

getExtendLocation = (sel) =>{
    if (sel.checked == true) {
        locationId.push(sel.value);
    } else {
        for (let i = 0; i < locationId.length; i++) {
            if (locationId[i] == sel.value) {
                locationId.splice(i, 1);
            }
        }
    }

    if (locationId.length == 1) {
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked == false) {
                inputs[i].setAttribute('disabled', 'disabled');
            }
        }
    } else {
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked == false) {
                inputs[i].removeAttribute('disabled');
            }
        }
    }
}

getExistingLocation = (sel) =>{
    if (sel.checked == true) {
        if(locationId.length == 0){
            locationId.push(sel.value);
            client.push(sel.id);
        }else if(sel.id == client[0]){
            locationId.push(sel.value);
        }else{
            alert('Silahkan pilih lokasi dengan klien yang sama..!!');
            sel.checked = false;
        }
    } else {
        for (let i = 0; i < locationId.length; i++) {
            if (locationId[i] == sel.value) {
                locationId.splice(i, 1);
                client.splice(i, 1);
            }
        }
    }

    if (locationId.length == 5) {
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked == false) {
                inputs[i].setAttribute('disabled', 'disabled');
            }
        }
    } else {
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked == false) {
                inputs[i].removeAttribute('disabled');
            }
        }
    }
}
getLocation = (sel) => {
    if (sel.checked == true) {
        locationId.push(sel.value);
    } else {
        for (let i = 0; i < locationId.length; i++) {
            if (locationId[i] == sel.value) {
                locationId.splice(i, 1);
            }
        }
    }

    if (category.value == "Videotron" || (category.value == "Signage" && type.value == "Videotron")) {
        if (locationId.length == 1) {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked == false) {
                    inputs[i].setAttribute('disabled', 'disabled');
                }
            }
        } else {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked == false) {
                    inputs[i].removeAttribute('disabled');
                }
            }
        }
    } else {
        if (locationId.length == 5) {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked == false) {
                    inputs[i].setAttribute('disabled', 'disabled');
                }
            }
        } else {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked == false) {
                    inputs[i].removeAttribute('disabled');
                }
            }
        }
    }
}

quotationCreate = () => {
    if (locationId.length == 0) {
        alert("Silahkan pilih lokasi terlebih dahulu...!!")
    } else {
        let objId = JSON.stringify(locationId);
        btnCreate.setAttribute('href', '/marketing/quotations/create-quotation/' + category.value + '/' + getType +
            '/' + objId + '/' + area.value +
            '/' + city.value);
        btnCreate.click();
    }
}

typeCheck = (sel) => {
    getType = sel.value;
}

typeServiceCheck = (sel) => {
    getType = sel.value;
}