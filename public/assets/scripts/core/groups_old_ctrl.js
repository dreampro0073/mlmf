app.controller('groupsCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        customers:[],
        day:'', 
        month:'', 
        year:'', 
    };
    $scope.group_id = 0;
    $scope.customer_id = 0;
    $scope.emi_collection_id = 0;

    $scope.group_details = {};
    $scope.groups_details = [];
    $scope.group = {};
    $scope.payForm = {};
    $scope.emi_collection_ids =[];
    $scope.customers_emis = [];
    $scope.selectCustomer = {
        valueField: 'id',
        labelField: 'name',
        maxItems:1,
        searchField: 'name',
        create: false,
            onInitialize: function(selectize){
        }
    }
    $scope.emi_type = 0;


    $scope.onChangePlan = function(){
        $scope.changePlan();
        $scope.formData.day = '';
    }
    $scope.changePlan = function(){

        DBService.postCall({ plan_id:$scope.formData.plan_id}, '/api/groups/select-plan').then((data) => {
            if (data.success) {
                $scope.emi_type = data.plan.emi_type;
           }
        });
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

    $scope.selectConfigBlock = {
        valueField: 'id',
        labelField: 'block_name',
        maxItems:1,
        searchField: 'block_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

    $scope.selectConfigPlans = {
        valueField: 'id',
        labelField: 'plan_name',
        maxItems:1,
        searchField: 'plan_name',
        create: false,
        onInitialize: function(selectize){
            // console.log('Initialized', selectize);
        }
    }

   $scope.addGroupInit = function () {
        $scope.loading = true;
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/init').then((data) => {
            if (data.success) {
                if(data.group){
                    $scope.formData = data.group;
                    $scope.getVillages();
                    $scope.changePlan();
                }
                $scope.blocks = data.blocks;
                $scope.plans = data.plans;
                $scope.customers = data.customers;
            } else {
                alert(data.message);
                window.location = base_url+'/admin/groups';
            }

        });
        $scope.loading = false;
    }
    

    $group_customers = [];

    $scope.getCLoanCard = () => {
           
        $scope.loan_loading= true;
        DBService.postCall({ group_id:$scope.group_id,customer_id:$scope.customer_id}, '/api/groups/get-cloan-card').then((data) => {
            if (data.success) {
                $scope.loan_loading= false;

                $scope.group = data.group;
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
    
    $scope.addUser = () => {
        console.log($scope.formData.customer_id);
    }
    $scope.storeGroup = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/old-groups/store').then((data) => {
            alert(data.message);
            
            if(data.success){
                $scope.actvateGroup(data.group_id);
                $scope.location = data.redirect_url;
            }   
        });
        $scope.loading = false;
    } 

    $scope.setCustomer = function(label, value, obj){
        console.log(obj);
        if (!$scope.formData.customers) {
            $scope.formData.customers = []; 
        }

        $scope.$apply(function(){
            var customer_id = $scope.formData.customers.indexOf(obj);
            console.log(customer_id); 
            if(customer_id === -1){
                $scope.formData.customers.push({
                    id : value,
                    name : label
                });
            }
            console.log($scope.formData.customers);
        });


    }


    $scope.removeCustomer = function(index){
        $scope.formData.customers.splice(index,1);
    }

    $scope.active_loading = false;

    $scope.actvateGroup = function(group_id) {

        $scope.active_loading = true;
        
        DBService.postCall({ group_id:group_id}, '/old-groups/activate-group').then((data) => {
            window.location = $scope.location;
        });
    }




});
