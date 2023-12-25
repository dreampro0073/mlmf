app.controller('IncomeCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.income_id = 0;
    $scope.index = '';
    $scope.incomes = [];
    
    $scope.formData = {multiple_income:[{

    }]};

    $scope.single_income = {
        
    };    

    $scope.income_accounts =[];
    $scope.searchData = { };


    $scope.init = function(){
        DBService.postCall($scope.searchData,'/api/income/init').then(function(data){
            if(data.success){
                $scope.incomes = data.incomes;   
            }
        }); 
    }
    $scope.onSearch = function(){
        $scope.init();
    }
    $scope.clearFilter = function(){
        $scope.searchData = { };
        $scope.init();
    }

    $scope.edit = function(){
        DBService.postCall({income_id:$scope.income_id},'/api/income/edit').then(function(data){
            if (data.success) {

                if (data.income) {
                    $scope.formData.multiple_income=[data.income];
                }
            }
        });
    }

    $scope.viewIncome = function(index){
        $scope.income = $scope.incomes[index];
        console.log($scope.income);
        var id = angular.element("#myModal");
        if (id) {
            id.modal("show");
        }

    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        console.log($scope.formData);
        DBService.postCall($scope.formData,'/api/income/store').then(function(data){
            if (data.success) {
                alert(data.message);
                window.location = base_url + '/admin/income';
            }else{
                alert(data.message);
            }
            $scope.processing = false;
        });
    }


    $scope.duplicate = function(){
        $scope.formData.multiple_income.push(JSON.parse(JSON.stringify(
            $scope.formData.multiple_income[$scope.formData.multiple_income.length - 1]
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
        $scope.single_income.attachment = '';
    }


    $scope.deleteIncome = function(income,index){
        $scope.income_id= income.id;
        if (confirm("Are You Sure ?") == true) {
            DBService.getCall('/api/income/delete/'+$scope.income_id).then(function(data){
                if (data.success) {
                    alert(data.message);
                    $scope.incomes.splice(index,1);

                }else{
                    alert(data.message);
                }
            });
        };
    }


    // $scope.typeSubmit = function(){
    //     DBService.postCall($scope.single_expense,'/api/income/store-type').then(function(data){
    //         if (data.success) {
    //             $scope.single_expense = {};
    //             $scope.expenses[$scope.index] = data.expense;

    //             $('#typeChangeModal').modal("hide");
    //         } else {
    //             alert(data.message); 
    //         }
    //     });
    // }

    $scope.removeFileX = function(){
        $scope.single_income.attachment = '';
    }


    $scope.addMore = function(){
        $scope.formData.multiple_income.push(JSON.parse(JSON.stringify($scope.single_income)));
    }

})
