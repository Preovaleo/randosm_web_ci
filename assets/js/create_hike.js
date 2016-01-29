function changeType() { // Add a text field if "Autre :" type is chosen
    if (document.getElementById("type").value == 'Autre :') {
        textField = document.createElement('input');
        textField.setAttribute("type", "text");
        textField.setAttribute("name", "otherType");
        textField.setAttribute("id", "otherType");
        document.getElementById("form-step-2").insertBefore(textField, document.getElementById("type").nextSibling);
    }
    if ($('#type').val() != 'Autre :' && document.getElementById("otherType")) { // The textfield is deleted if another type is selected and if the textfield exists
        document.getElementById("otherType").parentNode.removeChild(document.getElementById("otherType"));
    }
}


// Text fields ###############################################################
var form = document.forms["create-hike-form"];

function getPseudo() {
    if (form)
        return document.forms["create-hike-form"].elements.name.value;
}

function getCity() {
    if (form)
        return document.forms["create-hike-form"].elements.city.value;
}

function getDistance() {
    if (form)
        return document.getElementById('length').value;
}

function getTime() {
    if (form)
        return document.getElementById('time').value;
}

function getDescription() {
    if (form)
        return document.forms["create-hike-form"].elements.description.value;
}
// Type dropdown ##############################################################
function getType() {
    if (form)
        typeList = document.getElementById('type');
    return typeList.options[typeList.selectedIndex].innerHTML;
}
// Difficulty radio buttons ###################################################
function getDifficulty() {
    if (form) {
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type == 'radio' && inputs[i].checked) {
                return inputs[i].value;
            }
        }
    }
}



$(document).ready(function () {
    checkCity();
});

$("input[name=name]").on("keyup change", function () {
    checkCity();
});
$("input[name=city]").on("keyup change", function () {
    checkCity();
});

$(document.body).on('click', '.ui-corner-all', function () {
    checkCity();
});


function checkCity() { // Vérifie que la ville existe dans la base de donnée.
    if (getCity()) {
        var res = getCity().split(" ");
        return $.ajax({
            url: base_url + "my_hikes/checkCity",
            data: {
                cityField: res[0]
            },
            type: "POST",
            success: function (data) {
                if (data == "true" && $("input[name=name]").val() !== "") {
                    $(".step2").removeClass('disabled-button');
                } else {
                    $(".step2").addClass('disabled-button');
                }
            }
        });
    }
}




$("a.button.step1").click(function () {
    $('div#step2').hide();
    $('div#step1').show();
    $('div#step1').show();
});

$("a.button.step2").click(function () {

    if (!getPseudo()) {
        if (!document.getElementById('nameError')) {
            error = document.createElement('p');
            error.setAttribute('class', 'error');
            error.setAttribute('id', 'nameError');
            document.getElementById('step1').getElementsByClassName('errors')[0].appendChild(error);
            error.appendChild(document.createTextNode('Le champs "Nom" doit être renseigné.'));
        }
    }
    if (!getCity()) {
        if (!document.getElementById('cityError')) {
            error = document.createElement('p');
            error.setAttribute('class', 'error');
            error.setAttribute('id', 'cityError');
            document.getElementById('step1').getElementsByClassName('errors')[0].appendChild(error);
            error.appendChild(document.createTextNode('Le champs "Ville associée" doit être renseigné.'));
        }
    }
    if (getPseudo()) {
        if (document.getElementById('nameError')) {
            document.getElementById('nameError').parentNode.removeChild(document.getElementById('nameError'));
        }
    }
    if (getCity()) {
        if (document.getElementById('cityError')) {
            document.getElementById('cityError').parentNode.removeChild(document.getElementById('cityError'));
        }
    }
    if (getPseudo() && getCity()) {
        if (document.getElementById('nameError')) {
            document.getElementById('nameError').parentNode.removeChild(document.getElementById('nameError'));
        }
        if (document.getElementById('cityError')) {
            document.getElementById('cityError').parentNode.removeChild(document.getElementById('cityError'));
        }

        $('div#step1').hide();
        $('div#step3').hide();
        $('div#step2').show();
    }

});


$("a.button.step3").click(function () {
    $('div#step3').css({
        left: '0'
    });

    $('div#step2').hide();
    $('div#step4').hide();
    $('div#step3').show();
});


$("a.button.step4").click(function () {
    if ($('div.content').width() == $('div.wraper').width()) {
        $('div#step5').hide();
        setTimeout(function () {
            $('div#step3').hide();
            $('div#step4').show();
        }, 900);
    } else {
        $('div#step5').hide();
        $('div#step3').hide();
        $('div#step4').show();
    }


});


$("a.button.step5").click(function () {
    if (!getDistance() || getDistance() === 0) {
        if (!document.getElementById('distanceError')) {
            error = document.createElement('p');
            error.setAttribute('class', 'error');
            error.setAttribute('id', 'distanceError');
            document.getElementById('step4').getElementsByClassName('errors')[0].appendChild(error);
            error.appendChild(document.createTextNode('Le champs "Distance" doit être renseigné.'));
        }
    }

    if (getTime() === "" || getTime() == "00:00") {
        if (!document.getElementById('timeError')) {
            error = document.createElement('p');
            error.setAttribute('class', 'error');
            error.setAttribute('id', 'timeError');
            document.getElementById('step4').getElementsByClassName('errors')[0].appendChild(error);
            error.appendChild(document.createTextNode('Le champs "Durée" doit être renseigné.'));
        }
    }
    if (getDistance() && getDistance() !== 0) {
        if (document.getElementById('distanceError')) {
            document.getElementById('distanceError').parentNode.removeChild(document.getElementById('distanceError'));
        }
    }

    if (getTime()) {
        if (document.getElementById('timeError')) {
            document.getElementById('timeError').parentNode.removeChild(document.getElementById('timeError'));
        }
    }
    if (getDistance() && getDistance() !== 0 && getTime()) {
        if (document.getElementById('distanceError')) {
            document.getElementById('distanceError').parentNode.removeChild(document.getElementById('distanceError'));
        }
        if (document.getElementById('timeError')) {
            document.getElementById('timeError').parentNode.removeChild(document.getElementById('timeError'));
        }
        $('div#step4').hide();
        $('div#step6').hide();
        $('div#step5').show();
    }
});


$("a.button.step6").click(function () {
    $('div#step5').hide();
    $('div#step6').show();
});



/*
 **************************************************************************************************
 ******************************** Files uploading drag and drop zones *****************************
 **************************************************************************************************
 */
if (typeof Dropzone != 'undefined') {
    Dropzone.autoDiscover = false;

    /* Translation to French */
    Dropzone.prototype.dictDefaultMessage = "Faire glisser ici les fichiers à télécharger.";
    Dropzone.prototype.defaultOptions.dictFallbackMessage = "Votre navigateur ne supporte pas le téléchargement pas glisser-déposer.";
    Dropzone.prototype.defaultOptions.dictFallbackText = "Utilisez s'il-vous-plait le formulaire ci-dessous pour télécharger vos fichiers.";
    Dropzone.prototype.defaultOptions.dictFileTooBig = "Fichier trop lourd ({{filesize}} Mio). Taille max : {{maxFilesize}} Mio.";
    Dropzone.prototype.defaultOptions.dictInvalidFileType = "Vous ne pouvez pas télécharger de fichiers de ce type ici.";
    Dropzone.prototype.defaultOptions.dictResponseError = "Le serveur a répondu avec le code {{statusCode}}.";
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Annuler le téléchargement";
    Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Êtes-vous sûr de vouloir annuler le téléchargement ?";
    Dropzone.prototype.defaultOptions.dictRemoveFile = "Supprimer";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "Vous ne pouvez plus ajouter de fichier.";
    Dropzone.prototype.defaultOptions.autoProcessQueue = false; // Unable automatic uploading
    Dropzone.prototype.defaultOptions.parallelUploads = 9; // How many file uploads to process in parallel
    Dropzone.prototype.defaultOptions.addRemoveLinks = true; // Add remove button
    Dropzone.prototype.defaultOptions.uploadMultiple = true;

    var photoDropzone = new Dropzone("div#photo-dropzone", {
        url: base_url + "create_hike/file_upload/photo/",
        acceptedFiles: ".jpg, .jpeg, .png",
        maxFiles: 3,
        maxFilesize: 2, // MB
        init: function () {
            this.on("addedfile", function (file) {
                thumbnails(file, this);
            });
            this.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    console.log("Tout les fichiers photos ont été uploadés.");
                    if (audioDropzone.getQueuedFiles().length !== 0) {
                        audioDropzone.processQueue();
                    } else if (videoDropzone.getQueuedFiles().length !== 0) {
                        videoDropzone.processQueue();
                    } else {
                        document.location = base_url + "my_hikes";
                    }
                }
            });
        }
    });



    var audioDropzone = new Dropzone("div#audio-dropzone", {
        url: base_url + "create_hike/file_upload/audio/",
        acceptedFiles: "audio/*",
        maxFiles: 3,
        maxFilesize: 1, // MB
        init: function () {
            this.on("addedfile", function (file) {
                thumbnails(file, this);
            });
            this.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    console.log("Tout les fichiers audios ont été uploadés.");
                    if (videoDropzone.getQueuedFiles().length !== 0) {
                        videoDropzone.processQueue();
                    } else {
                        document.location = base_url + "my_hikes";
                    }
                }
            });
        }
    });

    var videoDropzone = new Dropzone("div#video-dropzone", {
        url: base_url + "create_hike/file_upload/video/",
        acceptedFiles: "video/*",
        maxFiles: 3,
        maxFilesize: 10, // MB
        init: function () {
            this.on("addedfile", function (file) {
                thumbnails(file, this);
            });
            this.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    console.log("Tout les fichiers vidéos ont été uploadés.");
                    document.location = base_url + "my_hikes";
                }
            });
        }
    });
}


function setDropzonesUrl(hikeId) {
    photoDropzone.options.url += hikeId;
    audioDropzone.options.url += hikeId;
    videoDropzone.options.url += hikeId;
}

function upload() {
    if (photoDropzone.getQueuedFiles().length !== 0) {
        photoDropzone.processQueue();
        console.log("------ Photos uploading ------");
    } else if (audioDropzone.getQueuedFiles().length !== 0) {
        audioDropzone.processQueue();
        console.log("------ Audios uploading ------");
    } else if (videoDropzone.getQueuedFiles().length !== 0) {
        videoDropzone.processQueue();
        console.log("------ Videos uploading ------");
    } else {
        document.location = base_url + "my_hikes";
    }
}






function thumbnails(file, dropzone) { // Set custom thumbnails for audio and video files
    if (file.type.match(/audio.*/)) {
        dropzone.emit("thumbnail", file, base_url + "assets/images/audio.png");
    }
    if (file.type.match(/video.*/)) {
        dropzone.emit("thumbnail", file, base_url + "assets/images/video.png");
    }
}


/*
 **************************************************************************************************
 **************************** Dropzones opening animations at step 6 ******************************
 **************************************************************************************************
 */
//
//    $('#step6 h1').on("click", function () {
//        $(this).animate({
//            'margin-bottom': '180px'
//        }, 500, function () {
//            $(this).next().fadeIn();
//        });
//
//    });

$('#step6 h1').on("click", function () {
    var h1 = $(this);

    if (h1.next().css("display") == "none") {
        AnimateRotate(0, h1.children());
        h1.animate({
            'margin-bottom': '180px'
        }, 600, function () {
            h1.next().fadeIn(800);
            h1.animate({
                'margin-bottom': '0'
            }, 200);

        });
    } else {
        AnimateRotate(90, h1.children());
        h1.next().fadeOut(400, function () {

            //                h1.animate({
            //                    'margin-bottom': '0'
            //                }, 300);
        });
        h1.animate({
            'margin-bottom': '180px'
        }, 0);
    }
});





//    $('#step6 h1').on("click", function () {
//        var h1 = $(this);
//        if (h1.next().css("display") == "none") {
//            h1.animate({
//                'margin-bottom': '180px'
//            }, 500, function () {
//                h1.next().fadeIn(500);
//                h1.animate({
//                    'margin-bottom': '0'
//                }, 0);
//
//            });
//        } else {
//
//            h1.next().fadeOut(500, function () {
//
//                //                h1.animate({
//                //                    'margin-bottom': '0'
//                //                }, 500);
//            });
//            h1.animate({
//                'margin-bottom': '180px'
//            }, 0);
//
//
//            h1.animate({
//                    'margin-bottom': '0'
//                },
//                1000);
//        }
//
//    });
function AnimateRotate(deg, element) {
    $({
        deg: 0
    }).animate({
        deg: deg
    }, {
        step: function (now, fx) {
            element.css({
                transform: "rotate(" + now + "deg)"
            });
        }
    });
}