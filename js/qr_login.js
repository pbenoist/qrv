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
