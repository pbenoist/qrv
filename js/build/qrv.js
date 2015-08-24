//
// http://localhost/qrv/php/SearchData.php?val=ky29zk8
// 
var app = angular.module('mainApp', ['ngRoute', 'ui.bootstrap' ,'ngSanitize']);
 
app.config(['$routeProvider', '$locationProvider', 
  function($routeProvider, $locationProvider) {
    $routeProvider
    .when('/Search', {
    templateUrl: 'template/search.html',
    controller: 'searchController'
      })
     .when('/Show/:animalID', {
    templateUrl: 'template/show.html',
    controller: 'showController'
      })
     .when('/Assistance', {
    templateUrl: 'template/assist.html',
    controller: 'assistController'
      })
     .when('/Adresse', {
    templateUrl: 'template/adresse.html',
    controller: 'adresseController'
      })
    .when('/Login', {
    templateUrl: 'template/login.html',
    controller: 'loginController'
      })
    .when('/Image', {
    templateUrl: 'template/image.html',
    controller: 'imageController'
      })
    .when('/Edit', {
    templateUrl: 'template/edit.html',
    controller: 'editController'
      })
    .when('/New', {
    templateUrl: 'template/new.html',
    controller: 'newController'
      })
    .when('/Info/:idInfo', {
    templateUrl: 'template/info.html',
    controller: 'infoController'
      })
    .when('/Contact', {
    templateUrl: 'template/contact.html',
    controller: 'contactController'
      })
    .otherwise({
        redirectTo: '/Search'
      }); 
    }  
]);

app.filter('makeUppercase', function () {
  return function (item) {
    return item.toUpperCase();
  };
});

app.run(['$rootScope', function($rootScope) {

  $rootScope.historiqueDone = 0;
  $rootScope.needReload = false;
  $rootScope.codepin = "";
  $rootScope.nuserie = "";
  $rootScope.email_proprio = "";
  $rootScope.login_menu = "Connexion";
  $rootScope.bFirst  = 0;         // Indicateur première connection
  $rootScope.bLogin  = 0;         // Indice utilisateur connecté
  $rootScope.valide  = false;     // Indice. Le compte est validé (Etat =1)


}]);


app.directive('rotate', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.degrees, function (rotateDegrees) {
//                console.log(rotateDegrees);
                var r = 'rotate(' + rotateDegrees + 'deg)';
                element.css({
                    '-moz-transform': r,
                    '-webkit-transform': r,
                    '-o-transform': r,
                    '-ms-transform': r
                });
            });
        }
    }
});

app.directive('capitalizeAll', ['$parse', function($parse) {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
          if (angular.isUndefined(inputValue))
            return '';
           var capitalized = inputValue.toUpperCase();
           if(capitalized !== inputValue) {
              modelCtrl.$setViewValue(capitalized);
              modelCtrl.$render();
            }         
            return capitalized;
         }
         var model = $parse(attrs.ngModel);
         modelCtrl.$parsers.push(capitalize);
         capitalize(model(scope));
     }
   };
}]);

app.directive('capitalizeFirst', ['$parse', function($parse) {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
          if (angular.isUndefined(inputValue))
              return '';
           var capitalized = inputValue.charAt(0).toUpperCase() +
               inputValue.substring(1);
           if(capitalized !== inputValue) {
              modelCtrl.$setViewValue(capitalized);
              modelCtrl.$render();
            }         
            return capitalized;
         }
         var model = $parse(attrs.ngModel);
         modelCtrl.$parsers.push(capitalize);
         capitalize(model(scope));
     }
   };
}]);


app.filter("telephoneFilter", function() {
  return function(input) {
    if (angular.isUndefined(input))
      return '';

    var s = input.trim();
    var ret = "";
    if (s.length == 10) {
      for (i=0; i<10; i++) {
        ret = ret + s.charAt(i);
        if (i>0 && i%2 == 1 && i!=9)  {
          ret = ret + ' ';
          }
        }
      }
    else
      ret = s;
    return ret;
  }
});


app.controller('adresseController', ['$rootScope', '$scope', '$http', '$location' , 

    function( $rootScope, $scope, $http,  $location) {
    $scope.send = false;
    $scope.email_proprio =          $rootScope.email_proprio;

    $scope.updateAdresse = function() {
        
//        alert('processform');
        // necessaire de récuperer "en dur" le password car en autocomplete,
        // angular ne renseigne pas ce champ (basé sur id de l'input field)
        //
        //
        jsn = { "codepin"           : $rootScope.codepin, 
                "email_proprio"     : $scope.email_proprio
                 } ;

        $http.post('php/updateAdresseMail.php', jsn )
        .success(function(data) 
        {
            obj = angular.fromJson(data);
            if (obj.ret == "ok")
            {
                $scope.send = true;
/*                $scope.message = "Adresse mise à jour.";   
                new_url = "/Show/" + $scope.codepin;
                $rootScope.needReload = true;
                $location.path(new_url);
*/            }
        })
        .error(function(data) 
        {
            $scope.message = "Anomalie. Echec de la mise à jour";           
        });
          
    }

    $scope.closeAdresse  = function() {
        new_url = "/Show/" + $rootScope.codepin;
        $location.path(new_url);    
    } 

}]);

app.controller('assistController', ['$rootScope', '$scope', '$http', '$routeParams', 
	function($rootScope, $scope, $http, $routeParams) {

}]);


app.controller('contactController', ['$rootScope', '$scope', '$http' , '$location',  
    function($rootScope, $scope, $http, $location) {

    $scope.codepin          = $rootScope.codepin;
    $scope.email_proprio    = $rootScope.email_proprio;  
    $scope.send = false;
    $scope.sendMail = function() {

        jsn = { "codepin"           : $rootScope.codepin, 
                "obj"           	: $scope.objet, 
                "message"     		: $scope.message 
                 } ;

        $http.post('php/send_mail_to_vethica.php', jsn )
        .success(function(data) 
        {
	    	$scope.send = true;
		});

    }

	$scope.closeMail  = function() {
        new_url = "/Show/" + $rootScope.codepin;
        $location.path(new_url);	
	} 
}]);

app.controller('editController', ['$rootScope', '$scope', '$http', '$location',
 function($rootScope, $scope, $http, $location) {

    $scope.password_proprio2 = ""
    $scope.password_proprio1 = "";
    $scope.info = "Modification";
    // DEV
    //    $rootScope.codepin = "AZER001";
    // DEV


    editAnimal = function() {

    $http.post('php/searchData.php', { "val" : $rootScope.codepin} )
    .success(function(data, status) {
    obj = angular.fromJson(data);
    $scope.id_animal        = obj.id_animal;

    $scope.status = status;
    $scope.data = data;
    if (obj.id_animal == 0)
    {
        // Impossible ! 
        new_url = "/New/" + $rootScope.codepin;
        $location.path(new_url);
    }               
    else
    {
        obj = angular.fromJson(data);
        $scope.id_animal        = obj.id_animal;
        $scope.codepin          = obj.codepin;
        $scope.nuserie          = obj.nuserie;
        $scope.email_proprio    = obj.email_proprio; 
        $scope.nom_animal       = obj.nom_animal; 
        $scope.nom_proprio      = obj.nom_proprio;
        $scope.prenom_proprio   = obj.prenom_proprio;
        $scope.tel_proprio      = obj.tel_proprio;
        $scope.code_postal      = obj.code_postal;
        $scope.espece           = obj.espece;
        $scope.date_naissance   = obj.date_naissance;
        $scope.race             = obj.race;
    }
    })

    .error(function(data, status) {
    $scope.data = data || "Request failed";
    $scope.status = status;         
    $scope.nom_animal       = "erreur"; 
    });

    }

    $scope.updateForm = function() {
        
        jsn = { "codepin"           : $scope.codepin, 
                "nuserie"           : $scope.nuserie, 
                "email_proprio"     : $scope.email_proprio, 
                "password_proprio"  : $scope.password_proprio,
                "nom_proprio"       : $scope.nom_proprio,
                "prenom_proprio"    : $scope.prenom_proprio,
                "tel_proprio"       : $scope.tel_proprio,
                "nom_animal"        : $scope.nom_animal,
                "code_postal"       : $scope.code_postal,
                "espece"            : $scope.espece,
                "date_naissance"    : $scope.date_naissance,
                "race"              : $scope.race
                 } ;

        $http.post('php/updateData.php', jsn )
        .success(function(data) 
        {
            // retour sur la fiche 
            new_url = "/Show/" + $scope.codepin;
            $location.path(new_url);
        })
        .error(function(data) 
        {
            $scope.message = "Erreur update";
        });
          
    }

    editAnimal();


    $scope.change_password = function() {
        $scope.mess = "";
    }

    $scope.do_change_password = function () {

        $scope.mess = "";
        if ($scope.password_proprio1 != $scope.password_proprio2)
        {
            $scope.mess = "Vos saisies ne sont pas identiques... Recommencez";
            $scope.password_proprio2 = ""
            $scope.password_proprio1 = "";
            return;
        }
        else
        {
            if ($scope.password_proprio1 == "")
                return;

            $scope.password_proprio = $scope.password_proprio1;

            jsn = { "codepin"           : $rootScope.codepin, 
                    "password_proprio"  : $scope.password_proprio
                 } ;

            $http.post('php/updatePassword.php', jsn )

            $('#passwordModal').modal('hide');
            return;
        }
    }
}]);


app.controller('imageController', ['$rootScope', '$scope', 
	function($rootScope, $scope) {

$scope.nuserie = $rootScope.nuserie;
$scope.codepin = $rootScope.codepin;

//$rootScope.needReload = true;


}]);

// common variables
var iBytesUploaded = 0;
var iBytesTotal = 0;
var iPreviousBytesLoaded = 0;
var iMaxFilesize = 4194304; // 4MB
var oTimer = 0;
var sResultFileSize = '';

function secondsToTime(secs) { // we will use this function to convert seconds in normal time format
    var hr = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hr * 3600))/60);
    var sec = Math.floor(secs - (hr * 3600) -  (min * 60));

    if (hr < 10) {hr = "0" + hr; }
    if (min < 10) {min = "0" + min;}
    if (sec < 10) {sec = "0" + sec;}
    if (hr) {hr = "00";}
    return hr + ':' + min + ':' + sec;
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
}

function fileSelected() {

    // hide different warnings
    document.getElementById('upload_response').style.display = 'none';
    document.getElementById('error').style.display = 'none';
    document.getElementById('error2').style.display = 'none';
    document.getElementById('abort').style.display = 'none';
    document.getElementById('warnsize').style.display = 'none';

    // get selected file element
    var oFile = document.getElementById('image_file').files[0];

    // filter for image files
    var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
    if (! rFilter.test(oFile.type)) {
        document.getElementById('error').style.display = 'block';
        return;
    }

    // little test for filesize
    if (oFile.size > iMaxFilesize) {
        document.getElementById('warnsize').style.display = 'block';
        return;
    }

    // get preview element
    var oImage = document.getElementById('preview');

    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e){

        // e.target.result contains the DataURL which we will use as a source of the image
        oImage.src = e.target.result;

        oImage.onload = function () { // binding onload event

            // we are going to display some custom image information here
            sResultFileSize = bytesToSize(oFile.size);
            document.getElementById('fileinfo').style.display = 'block';
            document.getElementById('filename').innerHTML = 'Name: ' + oFile.name;
            document.getElementById('filesize').innerHTML = 'Size: ' + sResultFileSize;
            document.getElementById('filetype').innerHTML = 'Type: ' + oFile.type;
            document.getElementById('filedim').innerHTML = 'Dimension: ' + oImage.naturalWidth + ' x ' + oImage.naturalHeight;
        };
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
}

function startUploading (){

    // cleanup all temp states
    iPreviousBytesLoaded = 0;
    document.getElementById('upload_response').style.display = 'none';
    document.getElementById('error').style.display = 'none';
    document.getElementById('error2').style.display = 'none';
    document.getElementById('abort').style.display = 'none';
    document.getElementById('warnsize').style.display = 'none';
    document.getElementById('progress_percent').innerHTML = '';
    var oProgress = document.getElementById('progress');
    oProgress.style.display = 'block';
    oProgress.style.width = '0px';

    // get form data for POSTing
    //var vFD = document.getElementById('upload_form').getFormData(); // for FF3
    var vFD = new FormData(document.getElementById('upload_form')); 

    // create XMLHttpRequest object, adding few event listeners, and POSTing our data
    var oXHR = new XMLHttpRequest();        
    oXHR.upload.addEventListener('progress', uploadProgress, false);
    oXHR.addEventListener('load', uploadFinish, false);
    oXHR.addEventListener('error', uploadError, false);
    oXHR.addEventListener('abort', uploadAbort, false);
    oXHR.open('POST', 'php/upload.php');
    oXHR.send(vFD);

    // set inner timer
    oTimer = setInterval(doInnerUpdates, 300);
}

function doInnerUpdates() { // we will use this function to display upload speed
    var iCB = iBytesUploaded;
    var iDiff = iCB - iPreviousBytesLoaded;

    // if nothing new loaded - exit
    if (iDiff == 0)
        return;

    iPreviousBytesLoaded = iCB;
    iDiff = iDiff * 2;
    var iBytesRem = iBytesTotal - iPreviousBytesLoaded;
    var secondsRemaining = iBytesRem / iDiff;

    // update speed info
    var iSpeed = iDiff.toString() + 'B/s';
    if (iDiff > 1024 * 1024) {
        iSpeed = (Math.round(iDiff * 100/(1024*1024))/100).toString() + 'MB/s';
    } else if (iDiff > 1024) {
        iSpeed =  (Math.round(iDiff * 100/1024)/100).toString() + 'KB/s';
    }

    document.getElementById('speed').innerHTML = iSpeed;
    document.getElementById('remaining').innerHTML = '| ' + secondsToTime(secondsRemaining);        
}

function uploadProgress(e) { // upload process in progress
    if (e.lengthComputable) {
        iBytesUploaded = e.loaded;
        iBytesTotal = e.total;
        var iPercentComplete = Math.round(e.loaded * 100 / e.total);
        var iBytesTransfered = bytesToSize(iBytesUploaded);

        document.getElementById('progress_percent').innerHTML = iPercentComplete.toString() + '%';
        document.getElementById('progress').style.width = (iPercentComplete * 2).toString() + 'px';
        document.getElementById('b_transfered').innerHTML = iBytesTransfered;
        if (iPercentComplete == 100) {
            var oUploadResponse = document.getElementById('upload_response');
            oUploadResponse.innerHTML = '<h1>Please wait...processing</h1>';
            oUploadResponse.style.display = 'block';
        }
    } else {
        document.getElementById('progress').innerHTML = 'unable to compute';
    }
}

function uploadFinish(e) { // upload successfully finished
    var oUploadResponse = document.getElementById('upload_response');
    oUploadResponse.innerHTML = e.target.responseText;
    oUploadResponse.style.display = 'block';

    document.getElementById('progress_percent').innerHTML = '100%';
    document.getElementById('progress').style.width = '100px';
    document.getElementById('filesize').innerHTML = sResultFileSize;
    document.getElementById('remaining').innerHTML = '| 00:00:00';

    clearInterval(oTimer);

}

function uploadError(e) { // upload error
    document.getElementById('error2').style.display = 'block';
    clearInterval(oTimer);
}  

function uploadAbort(e) { // upload abort
    document.getElementById('abort').style.display = 'block';
    clearInterval(oTimer);
}



app.controller('infoController', ['$rootScope', '$scope', '$http', '$routeParams', '$location' , 
	function($rootScope, $scope, $http, $routeParams, $location) {

    id = $routeParams.idInfo;
    if (id == 1)
        $scope.message_red = 'Insertion impossible';
    if (id == 2)
        $scope.message_red = 'Mise à jour  impossible';
    if (id == 3)
        $scope.message_red = 'Code erroné. 7 caractères pour code PIN ou 15 caractères pour code ISO';
    if (id == 4)
        $scope.message_red = 'Impossible de créer un code ISO sans code PIN';
    if (id == 5)
        $scope.message_red = 'QRV inexistant. Vérifiez les 7 caractères sur votre médaille';


}]);


app.controller('loginController', ['$rootScope', '$scope', '$http', '$location', 
        function($rootScope, $scope, $http, $location) {

    $scope.processLogin = function() {

        jsn =   {   "codepin"   : $rootScope.codepin, 
                    "password"  : $scope.password
                };

        $http.post('php/login.php', jsn )
        .success(function(data) 
        {
            if (data.error == 0)
            {
                $rootScope.login_menu = "Déconnection";
                $rootScope.bLogin = 1;
            }               
            else
            {
                $rootScope.login_menu = "Connexion";
                $rootScope.bLogin = 0;
                $scope.mess = "Mot de passe incorrect";
                return;
            }
            new_url = "/Show/" + $rootScope.codepin;
            $location.path(new_url);

        })
        .error(function(data) 
        {
                $rootScope.bLogin = 0;
        });

    }

    input_password = function() {
        $scope.mess = "";
    }

    $scope.send_password = function() {

        jsn =   {   "codepin"   : $rootScope.codepin
                };

        $http.post('php/send_password.php', jsn )
        .success(function(data) 
        {
            $scope.mess = "Mot de passe envoyé. Consultez votre messagerie";
        })
        .error(function(data) 
        {
            $scope.mess = "Erreur sur envoi Mot de passe";
        });


    }
}]);

app.controller('newController', ['$rootScope', '$scope', '$http', '$location' , 

    function( $rootScope, $scope, $http,  $location) {

    function getNuserie($rootScope, $scope, $http)    {
    
        jsn = { "codepin"           : $rootScope.codepin } ; 
        $http.post('php/search_nuserie.php', jsn )
        .success(function(data) {
            obj = angular.fromJson(data);
            if (obj.ret == 0){

                $rootScope.nuserie  = obj.nuserie;
                $scope.codepin      = $rootScope.codepin;
                $scope.nuserie      = $rootScope.nuserie;
            }
            else {
                nuserie_readonly = false;
            }
        
        });

    }

    $scope.isNuserieReadOnly = function() {
        return nuserie_readonly;
    }

    $scope.processForm = function() {
        
//        alert('processform');
        // necessaire de récuperer "en dur" le password car en autocomplete,
        // angular ne renseigne pas ce champ (basé sur id de l'input field)
        //
        $scope.password_proprio =  $("#password_proprio").val();
        //
        jsn = { "codepin"           : $scope.codepin, 
                "nuserie"           : $scope.nuserie, 
                "email_proprio"     : $scope.email_proprio, 
                "password_proprio"  : $scope.password_proprio,
                "nom_proprio"       : $scope.nom_proprio,
                "prenom_proprio"    : $scope.prenom_proprio,
                "tel_proprio"       : $scope.tel_proprio,
                "nom_animal"        : $scope.nom_animal,
                "code_postal"       : $scope.code_postal,
                "espece"            : $scope.espece,
                "date_naissance"    : $scope.date_naissance,
                "race"              : $scope.race
                 } ;

        $http.post('php/mail_new_user.php', jsn )
        .success(function(data) 
        {
            obj = angular.fromJson(data);
            if (obj.ret == "ok")
            {
                $scope.message = "Mail envoyé";   
                // On rappelle qu'un mot de passe a été envoyé ... 
                // todo
                // retour sur la fiche 
                new_url = "/Show/" + $scope.codepin;
                $rootScope.needReload = true;
                $location.path(new_url);
            }
            else
            {
                $location.path("/Info/1");
            }
        })
        .error(function(data) 
        {
            $scope.message = "Mail NON envoyé";           
        });
          
    }

    $scope.email_proprio = "";
    nuserie_readonly = true;
    getNuserie($rootScope, $scope, $http);

}]);

app.controller('searchController', ['$rootScope', '$scope', '$http', '$routeParams', '$location', 
	function( $rootScope, $scope, $http, $routeParams, $location) {
    
    $scope.processSearch = function()    {

        new_url = "/Show/" + $scope.str_search;
        $location.path(new_url);
    }

    count_tuple = function()    {
        $http.post('php/count_qrcode.php' )
	    .success(function(data, status) {
    	obj = angular.fromJson(data);
//	    $scope.count_qrcode     = obj.count_qrcode;
        $scope.cc1     = obj.cc1;
        $scope.cc2     = obj.cc2;
	})}

	count_tuple();
	
}]);


app.controller('showController', ['$rootScope', '$scope', '$http', '$routeParams', '$location' , '$window' ,
        function( $rootScope, $scope, $http, $routeParams, $location, $window) {

    $rootScope.valide   = true;
    $scope.image_name   = "../img/chien-1";
    $scope.rotate_value = 0;
    $scope.status_ready = false;

//    $rootScope.showContact = 1;
    
    // bouton de refresh sur le nom de l'animal
    //   nécessaire sur iphone qui ne rafraichit pas sur changement d'image ?
    refresh = function()    {
        $window.location.reload();
        $rootScope.needReload = false; 
    }

    unLogin = function()    {
        $rootScope.bLogin = 0;
        $rootScope.$apply();
    }

    $scope.message_red ="";
    if ($rootScope.needReload)
        refresh();

    searchAnimal = function() {

    $rootScope.codepin = $routeParams.animalID;

 	$http.post('php/searchData.php', { "val" : $routeParams.animalID} )
	.success(function(data, status) {
    $scope.info = data;   
    obj = angular.fromJson(data);

    $scope.id_animal     	= obj.id_animal;
	$scope.status = status;
    $scope.data = data;

    // Code erroné (mauvaise longueur... ou autre...)
    if (obj.error == 200)
    {
        $location.path("/Info/3");
        return;
    }

    // On ne peut pas créer à partir d'un code iso. Il faut un code PIN
    if (obj.error == 400)
    {
        $location.path("/Info/4");
        return;
    }
    if (obj.error == 500)
    {
        $location.path("/Info/5");
        return;
    }

    if (obj.error == 300)
    {
        // C'est la première connexion . Propriètaire ou autre. 
        $rootScope.bFirst = 1;
        $scope.nuserie          = obj.nuserie;
        $rootScope.nuserie      = obj.nuserie;
        $scope.codepin          = obj.codepin;
        $rootScope.codepin      = obj.codepin;
    }    	  		
    else

    {

        if (!$rootScope.historiqueDone) {
            
            $rootScope.historiqueDone = 1;

            $http.post('php/historique.php', { "val" : obj.codepin} )
            .success(function(data, status) {});
        }

        $scope.status_ready     = true;
        $scope.id_animal        = obj.id_animal;
        $scope.codepin          = obj.codepin;
        $scope.nuserie      	= obj.nuserie;
		$scope.nom_animal 		= obj.nom_animal; 
        $scope.email_proprio    = obj.email_proprio;
		$scope.nom_proprio 		= obj.nom_proprio;
        $scope.prenom_proprio   = obj.prenom_proprio;
        $scope.tel_proprio 		= obj.tel_proprio;
        $scope.image_name       = obj.image_name;
        $scope.rotate_value     = obj.rotate_image;

        $rootScope.nuserie          = $scope.nuserie;
        $rootScope.codepin          = $scope.codepin;
        $rootScope.email_proprio    = $scope.email_proprio;
/*
        $http.post('php/get_DB_image.php', { "id" : $scope.id_animal } )
            .success(function(data2) {
             obj = angular.fromJson(data2);
            $scope.image_data           = obj.img_data;
        });
*/

        if (obj.keepLogin == 1) {
            $rootScope.login_menu = "Déconnection";
            $rootScope.bLogin = 1;
        }

        if (obj.etat == 0) {
            $rootScope.valide   = false;
            $scope.message_red  = "<strong>Votre compte est enregistré.</strong><br>Vous avez reçu un courriel contenant un lien pour valider et compléter votre compte.<h5><small>Ce lien est valable 7 jours</small></h5>";
        }
        else {
            $rootScope.valide   = true;
            $scope.message_red  = "";
        }

	}
	})

	.error(function(data, status) {
	$scope.data = data || "Request failed";
	$scope.status = status;			
    $scope.status_ready     = false;
	$scope.nom_animal 		= "Please wait..."; 
	})

//    alert('fin function search');
	}
    

    // Première connection avec le qrcode courant
    // Permet au proprio de créer son compte
    $scope.isFirst = function() {
        if ($rootScope.bFirst) 
            return true;
        else 
            return false;
    }

    $scope.isValide = function() {
        if ($rootScope.valide) 
            return true;
        else 
            return false;
    }

    $scope.canConnect = function() {
        // premiere connexion
        if ($rootScope.bFirst) 
            return false;
        // compte non validé
        if (!$rootScope.valide) 
            return false;
        // Deja loggue
        if ($rootScope.bLogin) 
            return false;
        
        return true;
    }

    // Utilisateur logguer ? 
    $scope.isLogin = function() {
        if ($rootScope.bLogin) 
          return true;
        else 
          return false;
    }

    $scope.isReady = function() {
        if (angular.isUndefined($scope.status_ready))
            return false;
        val = $scope.image_name;
        return $scope.status_ready; 
    }

    $scope.rotate_image = function()   {
        $scope.rotate_value = parseInt($scope.rotate_value) + 90;
        if ($scope.rotate_value == 360)
            $scope.rotate_value = 0;
        $http.post('php/rotateImage.php', { "val" : $rootScope.codepin, "rotate" : $scope.rotate_value } );
    }



    searchAnimal();
 
}]);
