<!DOCTYPE html>
<html>
<head>
    <!-- <meta charset="utf-8"> -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700&amp;subset=devanagari,latin-ext" rel="stylesheet">
    <title>MLMF</title>
    <style type="text/css">
        .row{
            display: table;
            width: 100% ;
        }
        .row > div{
            display: table-cell;
            width: 50%;
        }  
        .bio span{
            font-weight: bold;
        }
        p{
            margin-bottom: 5px;
            line-height: 1.2;
            margin-top: 0;
        }
        p,li{
            font-size: 12px;
        }
        h4{
            margin-bottom: 5px;
            margin-top: 0;
        }
        h5{
            margin-bottom: 5px;
            margin-top: 0;

        }
        table tr td{
            text-align: center;
        }
        .vert{
            vertical-align: middle;
        }
       

        ul,li,.h-font{
            font-family: Hind, DejaVu Sans, sans-serif;
        }
        ul{
            padding-left: 0;
        }
        li{
            font-size: 12px;

        }
        .page-break {
            page-break-after: always;
        }
        td,th{
            font-size: 12px;
        }
    </style>
</head>
<body>
    <table cellpadding="3" cellspacing="0" border="1" style="width:100%">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Customer</th>
                <th>Aadhar</th>
                <th>EMI Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group_dates as  $group_date)
                <tr>
                    <th colspan="4">{{$group_date->group_name}}</th>
                </tr>
                @foreach($group_date->group_customers as $key => $customer)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{$customer->name}}
                        </td>
                        <td>
                            {{$customer->aadhaar_no}}
                        </td>
                        
                        <td>
                            {{$group_date->emi_amount}}
                        </td>
                    </tr>
                @endforeach
            @endforeach
             <tr>
                <th colspan="3">Total</th>
                <th colspan="1">{{$total_amount}}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>