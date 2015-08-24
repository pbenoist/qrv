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
