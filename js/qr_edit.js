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

