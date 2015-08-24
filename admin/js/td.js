var app = angular.module('directives', []);

app.directive('openDialog', 
   function() {
      var openDialog = {
         link :   function(scope, element, attrs) {
            function openDialog() {
              var element = angular.element('#myModal');
              var ctrl = element.controller();
              ctrl.setModel(scope.blub);
              element.modal('show');
            }
            element.bind('click', openDialog);
       }
   }
   return openDialog;
});

function SomeCtrl($scope) {
   $scope.blub = "Test";
}

function ModalCtrl($scope) {
   this.setModel = function(data) {
      $scope.$apply( function() {
         $scope.data = data;
      });
   }
   $scope.setModel = this.setModel;     
}