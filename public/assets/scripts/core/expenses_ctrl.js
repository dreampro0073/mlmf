app.controller('ExpenseCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.expense_id = 0;
    $scope.index = '';
    $scope.expenses = [];
    
    $scope.formData = {multiple_expense:[{
        expense_account: 1
    }]};

    $scope.single_expense = {
        
    };    

    $scope.expense_accounts =[];
    $scope.searchData = { };


    $scope.init = function(){
        DBService.postCall($scope.searchData,'/api/expenses/init').then(function(data){
            if(data.success){
                $scope.expenses = data.expenses;   
                $scope.expense_accounts =data.expense_accounts;
            }
        }); 
    }
    $scope.onSearch = function(){
        console.log($scope.searchData);
        $scope.init();
    }
    $scope.clearFilter = function(){
        $scope.searchData = { };
        $scope.init();
    }

    $scope.edit = function(){
        DBService.postCall({expense_id:$scope.expense_id},'/api/expenses/edit').then(function(data){
            if (data.success) {
                $scope.expense_accounts =data.expense_accounts;

                if (data.expense) {
                    $scope.formData.multiple_expense=[data.expense];
                }
            }
        });
    }

    $scope.viewExpense = function(index){
        $scope.expense = $scope.expenses[index];
        console.log($scope.expense);
        var id = angular.element("#myModal");
        if (id) {
            id.modal("show");
        }

    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        console.log($scope.formData);
        DBService.postCall($scope.formData,'/api/expenses/store').then(function(data){
            if (data.success) {
                alert(data.message);
                window.location = base_url + '/admin/expenses';
            }else{
                alert(data.message);
            }
            $scope.processing = false;
        });
    }


    $scope.duplicate = function(){
        $scope.formData.multiple_expense.push(JSON.parse(JSON.stringify(
            $scope.formData.multiple_expense[$scope.formData.multiple_expense.length - 1]
        )));
    }

    $scope.uploadFile = function (file,name,obj) {
        if(file){

            obj.uploading = true;
            var url = base_url+'/admin/uploadFile';
            Upload.upload({
                url: url,
                data: {
                    media: file
                }
            }).then(function (resp) {
                if(resp.data.success){
                    obj[name] = resp.data.media;

                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;
                console.log(resp.data.media);

            }, function (resp) {
                
                console.log('Error status: ' + resp.status);
                obj.uploading = false;

            }, function (evt) {
                
            });
        }
    }

    $scope.removeFileX = function(){
        $scope.single_expense.attachment = '';
    }


    $scope.deleteExpense = function(expense,index){
        $scope.expense_id= expense.id;
        if (confirm("Are You Sure ?") == true) {
            DBService.getCall('/api/expenses/delete/'+$scope.expense_id).then(function(data){
                if (data.success) {
                    alert(data.message);
                    $scope.expenses.splice(index,1);

                }else{
                    alert(data.message);
                }
            });
        };
    }

})
