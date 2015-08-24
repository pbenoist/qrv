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

app.run(['$rootScope', '$location', '$window', function($rootScope, $location, $window){
  $rootScope.$on('$routeChangeSuccess',function(event){
                 if (!$window.ga)
                    return;
                 $window.ga('send', 'pageview', { page: $location.path() });
        })

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

