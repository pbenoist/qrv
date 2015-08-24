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
