app.controller('plansCtrl', function($scope , $http, $timeout , DBService) {
    $scope.formData = {
        principal_amount : 0,
        interest_rate : 0,
        time_line : 0,
        emi_type : 0,
    };
    $scope.emi_types = [];
    $scope.plan_id = 0;
    $scope.interest_rate = 0;
    $scope.loading = false;

   

    $scope.addPlanInit = function () {
        $scope.loading = true;

        DBService.postCall({ plan_id:$scope.plan_id}, '/api/plans/init').then((data) => {
            if (data.success) {
                $scope.loading = false;

                $scope.formData = data.plan;
                $scope.emi_types = data.emi_types;
                $scope.days = data.days;
            
            }
            $scope.loading = false;

        });
    }
    
    $scope.storePlan = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/plans/store').then((data) => {
            
            
            if(data.success){
                alert(data.message);
                window.location = data.redirect_url;

            }  else{
                alert(data.message);
            } 

            $scope.loading = false;

        });
    }

    $scope.viewPlan = function(plan_id){
        $scope.plan_id = plan_id;
        DBService.postCall({plan_id : $scope.plan_id }, '/api/plans/view-plan').then((data) => {
            if(data.success){
                $scope.plan = data.plan;
                $("#planModal").modal("show"); 
            }
        });
    }

    
})
