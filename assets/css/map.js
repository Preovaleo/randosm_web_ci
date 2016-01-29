/* -------------------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------------------------- Map functions ------------------------------------------------------ */
/* -------------------------------------------------------------------------------------------------------------------------- */

var app = angular.module('RandOSM-CreateHike', ['ngAnimate']);

app.controller('StepsCtrl', function ($scope) {
    $scope.map = {};
    $scope.tileLayer = {};
    $scope.openedTile = {};
    $scope.steps = [];
    $scope.editable = false;


    /*
     *
     * editable defines if the map can be editable or not (to create or modify a hike)
     * container is the element where to search #map div
     *
     */
    $scope.newMap = function (editable, container) {
        $scope.editable = editable;
        if (container) {
            $('#map', container).replaceWith("<div id='map-active'><div id='help'></div></div>");

            $('#map-active', container).oncontextmenu = function () { // Unable right click on the map
                return false;
            };
        }


        // Create a map in the "map" div, set the view to a given place and zoom
        $scope.map = L.map('map-active').setView([1, 1], 13);

        // Add the OpenStreetMap tile layer to the map
        tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo($scope.map);


        // On récupère la ville entrée à l'étape 1 (le split sert à ne prendre que le premier mot, donc pas le numéro de département)
        var position = $("#autocomplete", $("#create-hike-form")).val();

        if (typeof position != 'undefined') {
            position = position.split(" ")[0];
            // Centre la carte sur la position donnée
            $.getJSON('http://nominatim.openstreetmap.org/search?format=json&limit=5&country=fr&city=' + position, function (data) {
                $scope.map.panTo(new L.LatLng(data[0].lat, data[0].lon));
            });
        }

        if (container) {
            $("#map-active", container).fadeIn();
            $scope.refreshMap();
        }


        if (editable) {
            $scope.map.addControl(helpCtrl)
                .addControl(expandCtrl)
                .on('click', $scope.onMapClick);
        } else {
            $("#steps").hide();
        }
    };





    $scope.refreshMap = function () {
        $scope.map.invalidateSize();
    };

    $scope.onMapClick = function (e) {
        if ($('#help').css('display') == "block") {
            $('#help').fadeOut();
        } else {
            var newMarker = L.marker(e.latlng);
            if ($scope.editable) {
                newMarker.options.draggable = true;
                newMarker.on('contextmenu', $scope.onMarkerRightClick).on('mouseover', $scope.onMarkerHover).on('dragend', $scope.refreshLine);
            }
            newMarker.comment = "";

            $scope.$apply($scope.steps.push(newMarker));
            angular.forEach($scope.steps, function (value) {
                value.addTo($scope.map);
            });

            $scope.refreshLine();

            var el = $('#steps'),
                curHeight = el.height(),
                autoHeight = el.css('height', 'auto').height();
            el.height(curHeight).animate({
                height: autoHeight
            }, 200, "swing");

        }
    };

    $scope.onMarkerRightClick = function (e) {
        var m = this;
        angular.forEach($scope.steps, function (value) {
            if (value.getLatLng() == m.getLatLng()) {
                $scope.map.removeLayer(value);
                var i = $scope.steps.indexOf(value);
                $scope.$apply($scope.steps.splice(i, 1));

                var el = $('#steps'),
                    h = (el.height()) - 80;
                el.delay(950).animate({
                    height: h
                }, 50, "swing");
            }
        });
        $scope.refreshLine();
    };

    $scope.onMarkerHover = function () {
        //        var m = this;
        //        angular.forEach($scope.steps, function (value) {
        //            if (value.marker.getLatLng() == m.getLatLng()) {
        //                var index = ($scope.steps.indexOf(value)) + 1;
        //                m.bindPopup("<strong>Étape n<sup>o</sup>" + index + "</strong>").openPopup();
        //            }
        //        });
    };

    $scope.refreshLine = function () {
        if (typeof polyline != 'undefined') {
            $scope.map.removeLayer(polyline);
        }
        var markersPositions = [];

        for (var i = 0; i < $scope.steps.length; i++) {
            markersPositions[i] = $scope.steps[i].getLatLng();
        }
        polyline = L.polyline(markersPositions, {
            color: '#00f47a',
            weight: 3,
            opacity: 0.9
        }).addTo($scope.map);
    };

    /*
     * Display the description, the pictures and the map for the selected hike
     */
    $scope.modifyInfos = function (e, hikeId) {
        var hikeCard = $(e).parents(".tile");
        var fields = $("[class$='-field']", hikeCard);
        console.log($(hikeCard));
        fields.each(function (index, value) {
            $(value).replaceWith("<input type='text' name='" + $(value).attr('name') + "' value='" + value.innerText + "'>");
        });
        
        var cityField = ("input[name='city']");
        $(cityField, hikeCard).replaceWith('<input type="text" name="city" id="autocomplete" autocomplete="off" class="ui-autocomplete-input" role="textbox" aria-autocomplete="list" aria-haspopup="true" value="' + $(cityField).val() + '">');

        var radios = $("[class$='-radio']", $('.tile-column1', hikeCard));
        radios.each(function (index, value) {
            $(value).replaceWith("<input type='radio' name='difficulty' value='1'> 1 <input type='radio' name='difficulty' value='2'> 2 <input type='radio' name='difficulty' value='3'> 3 <input type='radio' name='difficulty' value='4'> 4 <input type='radio' name='difficulty' value='5'> 5");
            $("input[type='radio'][value='" + value.innerText + "']", hikeCard).attr("checked", "checked");
        });

        $('.shoes', hikeCard).hide();
        $('.button2.icon-valid', hikeCard).show();
        $('.button2.icon-refuse', hikeCard).show();
        $('.button2.icon-map', hikeCard).show();
        $('.button2.icon-settings', hikeCard).hide();
    };

    /*
     * Display the description, the pictures and the map for the selected hike
     */
    $scope.modifyMap = function (e, hikeId) {
        $(e).parent().hide();
        var timeToWaitBeforeShowing = 0;
        if ($.isEmptyObject($scope.openedTile) === false) {
            timeToWaitBeforeShowing = 1300;
            $scope.hideDetails();
        }

        setTimeout(function () {
            var tile = $(e).parents('.tile');
            $(".expand-bar", tile).hide();

            $scope.newMap(true, tile);
            $scope.getHikeInfos(e, hikeId);

            $scope.openedTile.tile = tile;
            $scope.openedTile.hikeId = hikeId;

            $(".hike-description", tile).fadeIn();
            $(".expand-bar.show", tile).hide();

        }, timeToWaitBeforeShowing);
    };


    $scope.displayLineAndMarkers = function () {
        var markersPositions = [];
        $scope.steps.forEach(function (entry) {
            markersPositions.push(entry.getLatLng());
        });

        $scope.map.fitBounds(markersPositions);
        polyline = L.polyline(markersPositions, {
            color: '#00f47a',
            weight: 3,
            opacity: 0.9
        }).addTo($scope.map);


        $scope.steps.forEach(function (entry) {
            entry.addTo($scope.map);
        });

    };


    /*
     * Set in steps array the coordinates and comments of the hike passed in parameter
     */
    $scope.getHikeInfos = function (e, hikeId) {
        $.ajax({
            url: base_url + "my_hikes/getHikeInfos/" + hikeId,
            success: function (data) {
                $scope.steps = [];
                var array = JSON.parse("[" + data + "]");
                array[0].forEach(function (entry) {
                    var newMarker = L.marker(entry.latlng);
                    if ($scope.editable) {
                        newMarker.options.draggable = true;
                        newMarker.on('contextmenu', $scope.onMarkerRightClick).on('mouseover', $scope.onMarkerHover).on('dragend', $scope.refreshLine);
                    }
                    newMarker.comment = entry.comment;
                    $scope.steps.push(newMarker);
                });
                $scope.displayLineAndMarkers();
            },
            error: function (data) {

            }
        });
    };


    /*
     * Display hike pictures
     */
    $scope.getHikePhotos = function (tile, hikeId) {
        $.ajax({
            url: base_url + "my_hikes/getHikePictures/" + hikeId,
            success: function (data) {
                $scope.displayPictures(tile, JSON.parse(data));
            },
            error: function (data) {

            }
        });
    };

    /*
     * Create a html img node for each picture loaded
     */
    $scope.displayPictures = function (tile, picturesLinksArray) {
        picturesLinksArray.forEach(function (value, index) {
            if (index == picturesLinksArray.length - 1) {
                $(".hike-pictures", tile).append("<img src='" + value + "'>")
                    .ready(function () {
                        $(".hike-pictures", tile).fadeIn();
                    });
            } else {
                $(".hike-pictures", tile).append("<img src='" + value + "'>");
            }
        });
    };

    $scope.onMarkerClick = function () {

    };

    $scope.showDetails = function (e, hikeId) {
        var timeToWaitBeforeShowing = 0;
        if ($.isEmptyObject($scope.openedTile) === false) {
            timeToWaitBeforeShowing = 1300;
            $scope.hideDetails();
        }

        setTimeout(function () {
            var tile = $(e).parents('.tile');

            $scope.newMap(false, tile);
            $scope.getHikeInfos(e, hikeId);
            $scope.getHikePhotos(tile, hikeId);

            $scope.openedTile.tile = tile;
            $scope.openedTile.hikeId = hikeId;

            $(".hike-description", tile).fadeIn();
            $(".expand-bar.show", tile).hide();
            $(".expand-bar.hide", tile).show();

        }, timeToWaitBeforeShowing);
    };


    /*
     * Hide the description, the pictures and the map for the selected hike
     */
    $scope.hideDetails = function () {
        console.log("---- hideDetails ----");
        if ($.isEmptyObject($scope.openedTile) === false) {
            var tile = $scope.openedTile.tile;
            var hikeId = $scope.openedTile.hikeId;

            curHeight = tile.height();
            $(".expand-bar.hide", tile).hide();

            $('img', $(".hike-pictures", tile)).fadeOut(function () {
                $('img', $(".hike-pictures", tile)).remove();
            });

            tile.css('height', curHeight);

            $(".hike-description", tile).fadeOut(300);
            $(".expand-bar", tile).fadeOut();
            $('#map-active', tile).fadeOut(function () {
                $('#map-active', tile).remove();
                $("<div id='map'><div id='help'></div></div>").insertBefore($(".expand-bar", tile));
                tile.animate({
                    height: '147px'
                }, 500, function () {
                    /*Now, change the height to auto when animation finishes. 
                               Added to make the container flexible (Optional).*/
                    tile.css('height', 'auto');

                    $(".expand-bar.show", tile).show();
                });
            });
            $scope.openedTile = {};
        }
    };



    /*
     * Total hike length calculating. Put the length in distance field at step 4.
     */
    $scope.sendLength = function () {
        var totalLength = 0;
        if ($scope.steps[0]) {
            for (var i = 0; i < $scope.steps.length - 1; i++) {
                totalLength += $scope.steps[i].getLatLng().distanceTo($scope.steps[i + 1].getLatLng());
            }
            totalLength = Math.round(totalLength / 10) / 100; // Résultat en Kilomètres et arrondi à deux chiffres après la virgule.
        }
        document.getElementById('length').value = totalLength;
    };


    /**
     * Create the hike in the database and call updload function to upload files in the zip.
     */
    $scope.modifyHike = function (e, hikeId) {
        var form = $(e).parents('form');

        var steps = $scope.steps;

        var markers = [];
        angular.forEach(steps, function (value) {
            marker = {
                "latlng": value.getLatLng(),
                "comment": value.comment
            };
            markers.push(marker);
        });


        $.ajax({
            url: base_url + "create_hike/modify_infos",
            data: {
                dataString: form.serialize(),
                hikeId: hikeId
            },
            type: 'post',
            success: function (data) { // Retourne l'ID de la randonnée
            },
            error: function (data) {
                alert("Une erreur s'est malheureusement produite :/");
                console.log("ajax error :");
                console.log(data);
            }
        });


        $.ajax({
            url: base_url + "create_hike/modify_steps",
            data: {
                markers: JSON.stringify(markers),
                hikeId: hikeId
            },
            type: 'post',
            success: function (data) {
                console.log(data);
                document.location = base_url + "my_hikes";
            },
            error: function (data) {
                alert("Une erreur s'est malheureusement produite :/");
                console.log("ajax error :");
                console.log(data);
            }
        });
    };

    $scope.createHike = function () {
        if ($scope.steps[0]) {
            // Récupère les données utiles pour chaque étape de la randonnée
            $scope.markers = [];
            angular.forEach($scope.steps, function (value) {
                marker = {
                    "latlng": value.getLatLng(),
                    "comment": value.comment
                };
                $scope.markers.push(marker);
            });
        }

        $.ajax({
            url: base_url + "create_hike/create",
            data: {
                dataString: $("#create-hike-form").serialize(),
                markers: JSON.stringify($scope.markers)
            },
            type: 'post',
            success: function (data) { // Retourne l'ID de la randonnée
                setDropzonesUrl(data);
                upload();
            },
            error: function (data) {
                alert("Une erreur s'est malheureusement produite :/");
                console.log("ajax error :");
                console.log(data);
            }
        });
    };



    /*
     * Custom control on the map (expand, shrink, and help buttons).
     */
    var ExpandControl = L.Control.extend({
        options: {
            position: 'topright',
            expandText: "",
            expandTitle: "Agrandir la carte"
        },
        onAdd: function (t) {
            var e = "leaflet-control-expand",
                i = L.DomUtil.create("div", e + " leaflet-bar");
            return this._map = t,
            this._expandButton = this._createButton(this.options.expandText, this.options.expandTitle, e, i, this._expand, this),
            t.on("_expand", this._updateDisabled, this), i;
        },
        _expand: function () {
            $('nav').fadeOut(300);
            setTimeout(function () {
                $('div.content').animate({
                    width: '100%'
                }, 300, function () {
                    $('div#map-active').animate({
                        height: '420px'
                    }, 300, function () {
                        expandCtrl.removeFrom($scope.map);
                        $scope.map.addControl(shrinkCtrl);
                    });
                });
                setTimeout(function () {
                    $scope.refreshMap();
                }, 200);
            }, 300);
        },
        _createButton: function (t, e, i, n, s, a) {
            var r = L.DomUtil.create("a", i, n);
            r.innerHTML = t;
            r.href = "#";
            r.title = e;
            var h = L.DomEvent.stopPropagation;
            return L.DomEvent.on(r, "click", h).on(r, "mousedown", h).on(r, "dblclick", h).on(r, "click", L.DomEvent.preventDefault).on(r, "click", s, a).on(r, "click", this._refocusOnMap, a), r;
        }
    });


    var ShrinkControl = L.Control.extend({
        options: {
            position: 'topright',
            expandText: "",
            expandTitle: "Rétrécir la carte"
        },
        onAdd: function (t) {

            var e = "leaflet-control-shrink",
                i = L.DomUtil.create("div", e + " leaflet-bar");
            return this._map = t,
            this._expandButton = this._createButton(this.options.expandText, this.options.expandTitle, e, i, this._shrink, this), i;
        },
        _shrink: function () {
            $scope.shrink();
        },
        _createButton: function (t, e, i, n, s, a) {
            var r = L.DomUtil.create("a", i, n);
            r.innerHTML = t;
            r.href = "#";
            r.title = e;
            var h = L.DomEvent.stopPropagation;
            return L.DomEvent.on(r, "click", h).on(r, "mousedown", h).on(r, "dblclick", h).on(r, "click", L.DomEvent.preventDefault).on(r, "click", s, a).on(r, "click", this._refocusOnMap, a), r;
        }
    });

    var HelpControl = L.Control.extend({
        options: {
            position: 'topright',
            helpText: "",
            helpTitle: "Afficher l'aide"
        },
        onAdd: function (t) {

            var e = "leaflet-control-help",
                i = L.DomUtil.create("div", e + " leaflet-bar");
            return this._map = t,
            this._helpButton = this._createButton(this.options.helpText, this.options.helpTitle, e, i, this._help, this), i;
        },
        _help: function () {

            if ($('#help').css('display') == "block")
                $('#help').fadeOut();
            else
                $('#help').fadeIn();
        },
        _createButton: function (t, e, i, n, s, a) {
            var r = L.DomUtil.create("a", i, n);
            r.innerHTML = t;
            r.href = "#";
            r.title = e;
            var h = L.DomEvent.stopPropagation;
            return L.DomEvent.on(r, "click", h).on(r, "mousedown", h).on(r, "dblclick", h).on(r, "click", L.DomEvent.preventDefault).on(r, "click", s, a).on(r, "click", this._refocusOnMap, a), r;
        }
    });

    var expandCtrl = new ExpandControl();
    var shrinkCtrl = new ShrinkControl();
    var helpCtrl = new HelpControl();

    var greenIcon = L.icon({
        iconUrl: base_url + "assets/images/green-marker.png",
        iconRetinaUrl: base_url + "assets/images/green-marker-x2.png",
        shadowUrl: base_url + "assets/images/marker-shadow.png",
        shadowRetinaUrl: base_url + "assets/images/marker-shadow-x2.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    $scope.shrink = function () {
        $('div#map-active').animate({
            height: '300px'
        }, 300, function () {
            $('div.content').animate({
                width: '72.65625%',

            }, 300, function () {
                $('nav').fadeIn(300, function () {
                    shrinkCtrl.removeFrom($scope.map);
                    $scope.map.addControl(expandCtrl);
                });

            });
        });
    };
});