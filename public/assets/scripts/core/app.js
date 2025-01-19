var app = angular.module('app', [
	'jcs-autoValidate',
  'ngFileUpload',
  'selectize'
]);

angular.module('jcs-autoValidate')
    .run([
    'defaultErrorMessageResolver',
    function (defaultErrorMessageResolver) {
        defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
          errorMessages['patternInt'] = 'Please fill a numeric value';
          errorMessages['patternFloat'] = 'Please fill a numeric/decimal value';
        });
    }
]);

app.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});

app.directive('autoComplete', function() {
    return {
      restrict: 'A',
      link: function(scope, elem, attr, ctrl) {
          
          var searchtype = attr.searchtype;
          
          elem.autocomplete({
              source: base_url+'/api/search-'+searchtype,
              minLength: 2,
              select: function(event,ui){
                event.preventDefault();

                if(searchtype == "customers"){
                  scope.setCustomer(ui.item.label, ui.item.value, ui.item);
                }
              }
          });
      }
    };
});