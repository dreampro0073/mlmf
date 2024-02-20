app.controller('BankCtrl', function($scope , $http, $timeout , DBService) {
    $scope.searchData = {};
    $scope.formData = {};
    $scope.loading = false;
    $scope.banking = []; 
    $scope.cash_expense = 0;
    $scope.upi_expense = 0;
    $scope.expense = 0;
    $scope.cash_income = 0;
    $scope.upi_income = 0;
    $scope.income = 0;
    $scope.cash_invest = 0;
    $scope.upi_invest = 0;
    $scope.invest = 0;
    $scope.balance = 0;
        
    $scope.init = function () {
        $scope.loading = true;

        DBService.postCall($scope.searchData, '/api/banking/init').then((data) => {
            if (data.success) {
                $scope.banking = data.banking;
                $scope.cash_expense = data.cash_expense;
                $scope.upi_expense = data.upi_expense;
                $scope.expense = data.expense;
                $scope.cash_income = data.cash_income;
                $scope.upi_income = data.upi_income;
                $scope.income = data.income;
                $scope.cash_invest = data.cash_invest;
                $scope.upi_invest = data.upi_invest;
                $scope.invest = data.invest;
                $scope.balance = data.balance;
            }
            $scope.loading = false;

        });
    }

    $scope.clearFilter = function(){
        $scope.searchData = {};
        $scope.init();
    }

    $scope.addNew = function(){
        $("#addModal").modal("show"); 
    }

    $scope.storeTransaction = function () {
        $scope.loading = true;

        DBService.postCall($scope.formData, '/api/banking/store').then((data) => {
            alert(data.message);
            $scope.loading = false;
            if(data.success){
                $scope.formData = {};
                $("#addModal").modal("hide"); 
                $scope.init();
            }

        });
    }

    
})
