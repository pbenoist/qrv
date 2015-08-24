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
