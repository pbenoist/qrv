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
