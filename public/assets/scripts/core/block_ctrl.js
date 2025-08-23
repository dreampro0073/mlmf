app.controller('blockCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.loading = false;
    $scope.formData = {
        state_id:0,
        district_id : 0,
        village_name :0,
    };
    
    $scope.states = [];
    $scope.districts = [];

    $scope.getStates = function(){
        DBService.postCall($scope.formData, '/api/states').then((data) => {
            if (data.success) {
                $scope.states = data.states;
            }
        });
    }

    $scope.fetchDistricts = function(){
        if(!$scope.formData.id){
             $scope.formData.district_id = 0;
        }
        DBService.postCall($scope.formData, '/api/districts').then((data) => {
            if (data.success) {
                $scope.districts = data.districts;
            }
        });
    }


    $scope.addBlockInit = function () {
        $scope.loading = true;

        DBService.postCall({ block_id:$scope.block_id}, '/api/blocks/init').then((data) => {
            if (data.success) {
                $scope.loading = false;
                
                if(data.block){
                    $scope.formData = data.block;
                    $scope.fetchDistricts();
                }
            }
            $scope.loading = false;

        });
    }

    $scope.storeBlock = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/blocks/store').then((data) => {
            if(data.success){
                alert(data.message);
                window.location = data.redirect_url;
            }  else{
                alert(data.message);
            } 
            $scope.loading = false;

        });
    }

})
