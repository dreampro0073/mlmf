app.controller('villageCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.loading = false;
    $scope.formData = {
        state_id:0,
        city_id : 0,
        block_id : 0,
        village_name :'',
    };
    
    $scope.states = [];
    $scope.districts = [];
    $scope.blocks = [];

    $scope.getStates = function(){
        DBService.postCall($scope.formData, '/api/states').then((data) => {
            if (data.success) {
                $scope.states = data.states;
            }
        });
    }

    $scope.fetchDistricts = function(){
        if(!$scope.formData.id){
             $scope.formData.city_id = 0;
        }
        DBService.postCall($scope.formData, '/api/districts').then((data) => {
            if (data.success) {
                $scope.districts = data.districts;
            }
        });
    }
    $scope.getBlocks = function(){

        DBService.postCall({district_id:$scope.formData.city_id}, '/api/blocks').then((data) => {
            if (data.success) {
                $scope.blocks = data.blocks;
            }
        });
    }


    $scope.addVillageInit = function () {
        $scope.loading = true;

        DBService.postCall({ village_id:$scope.village_id}, '/api/villages/init').then((data) => {
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
        DBService.postCall($scope.formData, '/api/villages/store').then((data) => {
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
