app.controller('groupsCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.loading = false;
    $scope.formData = {
        customers:[],
        day:'', 
    };
    $scope.group_id = 0;
    $scope.customer_id = 0;
    $scope.emi_collection_id = 0;

    $scope.customer = {};
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
    $scope.partData = {};
    $scope.emi_type = 0;

    $scope.editNotPaid = (emi_collection_id) => {
        $scope.emi_collection_id = emi_collection_id;
        DBService.postCall({ emi_collection_id:$scope.emi_collection_id}, '/api/groups/collection/pay').then((data) => {
            $scope.payForm = data.pay_collection;
            $("#newsModal").modal("show"); 
        });
    }
    $scope.submitForm = () => {
        DBService.postCall($scope.payForm, '/api/groups/collection/paid').then((data) => {
            $scope.payForm = {};
            $("#newsModal").modal("hide"); 
        });
    }

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
    
    $scope.viewGroupInit = function () {
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/init-view').then((data) => {
            if (data.success) {
                $scope.groups_details = data.group;
            }
        });
    }
    
    $scope.collectionInit = function () {
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/collection-view').then((data) => {
            if (data.success) {
                $scope.group_dates = data.group_dates;
            }
        });
    }

    $group_customers = [];

    $scope.viewCollection = function () {
        $scope.c_loading= true;
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/view-collection').then((data) => {
            if (data.success) {
                $scope.c_loading= false;

                $scope.group = data.group;
                $scope.group_customers = data.group.group_dates[0].customers;

                // console.log($scope.group_customers+'h');
            }
        });
    }

    $scope.getLoanCard = () => {
       
        $scope.loan_loading= true;
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/get-loan-card').then((data) => {
            if (data.success) {
                $scope.loan_loading= false;

                $scope.group = data.group;
            }
        });
    }
    $scope.getCLoanCard = () => {
           
        $scope.loan_loading= true;
        DBService.postCall({ group_id:$scope.group_id,customer_id:$scope.customer_id}, '/api/groups/get-cloan-card').then((data) => {
            if (data.success) {
                $scope.loan_loading= false;

                $scope.group = data.group;
            }
        });
    }
     
    // $scope_customers = [];
    // $scope.addC = (item) => {
    //     for (var i = 0; i < $scope.group.group_dates.length; i++) {
    //         var group_date = $scope.group.group_dates[i];
    //         for (var j = 0; j < group_date.customers.length; j++) {
    //             var customer = group_date.customers[j];
    //             if(customer.customer_id == item.customer_id && item.emi_date == customer.emi_date){
    //                 customer.is_checked = customer.is_checked == true ? false : true;
    //                 console.log('yes');
    //             }else{
    //                 console.log('no');
    //             }

    //             // console.log(customer);
    //         }
    //     }
    // }

    $scope.addC = (emi_collection_id) => {
        // console.log(emi_collection_id);return;

        var idx = $scope.emi_collection_ids.indexOf(emi_collection_id);

        console

        if(idx > -1){
            $scope.emi_collection_ids.splice(idx,1);
        }else{
            $scope.emi_collection_ids.push(emi_collection_id);
        }

        // console.log($scope.emi_collection_ids);
    }

    $scope.saveCollecion = () => {
        // console.log($scope.group);
        DBService.postCall({emi_collection_ids:$scope.emi_collection_ids}, '/api/groups/save-collection').then((data) => {
            if (data.success) {
                $scope.viewCollection();
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
        // console.log($scope.formData);return;
        DBService.postCall($scope.formData, '/api/groups/store').then((data) => {
            alert(data.message);
            
            if(data.success){
                window.location = data.redirect_url;
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

    $scope.groupEMIInit = function(){
        DBService.postCall({ group_id:$scope.group_id}, '/api/groups/emi-status').then((data) => {
            if (data.success) {
                $scope.group_details = data.group;
                $scope.customers_emis = data.customers_emis;
            }
        });
    }
    $scope.active_loading = false;

    $scope.actvateGroup = function(group_id) {

        $scope.active_loading = true;

        
        DBService.postCall({ group_id:group_id}, '/api/groups/activate-group').then((data) => {
            $scope.active_loading = false;
            window.location = base_url+'/admin/groups';
 
        });
    }

    $scope.purpose= "";
    $scope.purpose_loading = false;
    $group_customer_id = 0;
    $scope.group_customer_id = 0;

    $scope.openPurpsoeModal = (group_customer_id,purpose) => {
        $scope.group_customer_id = group_customer_id;
        $scope.purpose = purpose;
        $("#purpose-modal").modal("show");
    }


    $scope.submitPurpose= () => {

        // console.log($scope.group_customer_id);return;
        $scope.purpose_loading = true;
    
        DBService.postCall({ purpose:$scope.purpose}, '/api/groups/store-purpose/'+$scope.group_customer_id).then((data) => {
            $scope.purpose_loading = false;
            $scope.purpose = "";
            $("#purpose-modal").modal("hide");
        });
    }

    $scope.payOldEMI = function(emi_collection_id){
        $scope.emi_collection_id = emi_collection_id;
        DBService.postCall({ emi_collection_id : $scope.emi_collection_id},'/api/groups/get-penalty').then((data) => {
            if(data.success){
                $scope.formData = data.penalty_emi;
                $("#payOldEMI-modal").modal("show");
            } else {
                alert(data.message);
            }
        });
    }    

    $scope.onSubmitPenalty = function(){
        DBService.postCall($scope.formData,'/api/groups/store-penalty').then((data) => {
            if(data.success){
                alert(data.message);
                $("#payOldEMI-modal").modal("hide");
                $scope.viewCollection();
            }
        });
    }

    $scope.collectInAdvanced = function(emi_collection_id){
        if (confirm("Are You Sure ?") == true) {
            DBService.postCall({ emi_collection_id : emi_collection_id},'/api/groups/advanced-collect').then((data) => {
                $scope.viewCollection();
            });
        }
    }


    $scope.payPartEMI = function(emi_collection_id){
        $scope.emi_collection_id = emi_collection_id;
        DBService.postCall({ emi_collection_id : $scope.emi_collection_id},'/api/groups/get-penalty').then((data) => {
            if(data.success){
                $scope.partData = data.penalty_emi;
                console.log($scope.partData);
                $("#payPartEMI-modal").modal("show");
            } else {
                alert(data.message);
            }
        });
    }

    $scope.onSubmitPart = function(){
        console.log($scope.partData);
        DBService.postCall($scope.partData,'/api/emi-part').then((data) => {
            if(data.success){
                alert(data.message);
                $("#payPartEMI-modal").modal("hide");
                $scope.viewCollection();
            }
        });
    }

    $scope.collectOldBalance = function(customer_id){
        if (confirm("Are You Sure ?") == true) {
            DBService.postCall({ customer_id : customer_id},'/api/old-collect').then((data) => {
                alert(data.message);
                $scope.viewCollection();
            });
        }
    }

    $scope.uploadFile = function (file,name,obj) {
        $scope.customer = obj;
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
                    $scope.customer.invoice = resp.data.media; 
                    $scope.updateInvoice();
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
    $scope.removeFile = function(customer){
        $scope.customer = customer;
        $scope.customer.invoice = "";
        $scope.updateInvoice();
    }

    $scope.updateInvoice = function(){
        DBService.postCall($scope.customer, '/api/groups/update-invoice').then((data) => {
             alert();
        });
    
    }

});

app.controller('loanCardCtrl', function($scope , $http, $timeout , DBService) {
    
    $scope.group = {};
    $scope.group_id = 0;
    $scope.customer_id = 0;
    $scope.customer = {};
    $scope.emi_collection_id = 0;
    $scope.loan_loading = 0;

   $scope.getCLoanCard = () => {
           
        $scope.loan_loading= true;
        DBService.postCall({ group_id:$scope.group_id,customer_id:$scope.customer_id}, '/api/groups/get-cloan-card').then((data) => {
            if (data.success) {
                $scope.loan_loading= false;

                $scope.group = data.group;
                $scope.customer = data.customer;
            }
        });
    }
    $scope.printCard = function() {
        var printContents = document.getElementById("loan-card").innerHTML;
        var popupWin = window.open('', '_blank', 'width=300,height=300');
        popupWin.document.open();
        popupWin.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + printContents + '</body></html>');
        popupWin.document.close();
    } 

    
     

})
