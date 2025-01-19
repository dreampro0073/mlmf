app.controller('clientsCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.loading = false;
    $scope.formData = {
        state_id : 0,
        district_id : 0,
        block_id :0,
        village_id:0,
    };
    $scope.guarantorData = {};
    $scope.client = {};
    $scope.client_id = 0;
    $scope.states = [];
    $scope.districts = [];
    $scope.state_id = 0;
    $scope.blocks = [];
    $scope.villages = [];
    $scope.banks = [];

    $scope.selectConfig = {
        valueField: 'id',
        labelField: 'state_name',
        maxItems:1,
        searchField: 'state_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

    $scope.selectConfigVillage = {
        valueField: 'id',
        labelField: 'village_name',
        maxItems:1,
        searchField: 'village_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

    $scope.selectConfigVillage = {
        valueField: 'id',
        labelField: 'village_name',
        maxItems:1,
        searchField: 'village_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

    $scope.selectConfigDis = {
        valueField: 'id',
        labelField: 'city_name',
        maxItems:1,
        searchField: 'city_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

    $scope.addClientInit = function () {
        DBService.postCall({ client_id:$scope.client_id}, '/api/clients/init').then((data) => {
            if (data.success) {
                $scope.states = data.states;
                $scope.banks = data.banks;
                if(data.client){
                    $scope.formData = data.client;
                    $scope.fetchDistricts();

                    setTimeout($scope.getBlocks(), 100000);
                    // $scope.getBlocks();
                    $scope.getVillages();
                }
                if(data.guarantor){
                    $scope.guarantorData = data.guarantor;
                }
            }
        });
    }
    
    $scope.clientDetails = function () {
        DBService.postCall({ client_id:$scope.client_id}, '/api/clients/details').then((data) => {
            if (data.success) {
                $scope.client = data.client;
                $scope.guarantorData = data.guarantorData;
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

    $scope.getBlocks = function(){

        DBService.postCall({district_id:$scope.formData.district_id}, '/api/blocks').then((data) => {
            if (data.success) {
                $scope.blocks = data.blocks;
            }
        });
    }

    $scope.getVillages = function(){
        DBService.postCall({block_id:$scope.formData.block_id}, '/api/villages').then((data) => {
            if (data.success) {
                $scope.villages = data.villages;
            }
        });
    }

    $scope.storeClient = function () {
        $scope.loading = true;
        $scope.clientData = {
            'formData' : $scope.formData,
            'guarantorData' : $scope.guarantorData,
        }
        DBService.postCall($scope.clientData, '/api/clients/store').then((data) => {
            alert(data.message);
            
            if(data.success){
                window.location = data.redirect_url;
            }  
            $scope.loading = false; 
        });
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
    $scope.removeFile = function(file_name){
        $scope.formData[file_name] = '';
    }
    
    $scope.removeGuarantorFile = function(file_name){
        $scope.guarantorData[file_name] = '';
    }





})

app.controller('dashCtrl', function($scope , $http, $timeout , DBService, Upload) {
    
    $scope.pending_list = [];

    $scope.dashInit = function(){
        DBService.postCall({ client_id:$scope.client_id}, '/api/dashboard/pending-list').then((data) => {
            if (data.success) {
                $scope.pending_list = data.pending_list;
            }
        });
    }





})
