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

