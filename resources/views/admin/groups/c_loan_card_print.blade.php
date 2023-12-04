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
       /* @font-face {
          font-family: "kurti";
          font-style: normal;
          font-weight: normal;
          src: url(kurti/KrutiDev010Regular.ttf) format("truetype");
        }*/
        /*@font-face {
          font-family: Hind;
          font-style: normal;
          font-weight: normal;
          src: url(http://example.com/hind.ttf) format('truetype');
        }*/

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
     <div class="row personal-bio" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <div class="bio row vert">
               <div>
                   <img src="{{url('assets/img/logo.png')}}" style="height:60px;width:auto;"> &nbsp;&nbsp;
               
               </div>
               <div>
                   <img src="{{url('assets/img/s_logo.png')}}" style="height:35px;width:auto;">
               </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            

        </div>

    </div>
    <div class="row personal-bio" style="height:160px;">
        <div class="col-md-6">
            <div class="bio">
                <h4>
                    Group Name: {{$group->group_name}}
                </h4>
                <h4>
                    Customer's Details
                </h4>
                <p>
                   Customer ID: <span>{{$customer->unique_id}}</span>
                </p>
                <p>
                    Name: <span>{{$customer->name}}</span>
                </p>
                <p>
                    Fathers/Husband Name : <span>{{$customer->father_husband_name}}</span>
                </p>
                <p>
                    Mobile : <span>{{$customer->mobile}}</span>
                </p>
                <p>
                    Guaranter Name : <span>{{$customer->guarantor_name}}</span>
                </p>
                <p>
                    Guaranter Mobile : <span>{{$customer->guarantor_mobile}}</span>
                </p>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <div class="row">
                <div class="image">
                    @if(isset($customer->joint_photo))

                        <img src="{{url($customer->joint_photo)}}" style="width:200pxpx;height: 150px;object-fit: cover;float: right;">
                        
                    @endif
                </div>
                <!-- <div class="image" style="padding-left: 10px;">
                    @if(isset($customer->guarantor_photo))
                        <img src="{{url($customer->guarantor_photo)}}" style="width:120px;height: 180px;object-fit: cover;">
                        <p>Guarantor Photo</p>
                    @endif

                </div> -->
            </div>
            
        </div>

    </div>
    <hr>
    <div class="row personal-bio mb-4">
        <div class="col-md-6">
            <div class="bio">
                <!-- <p>
                    Borrower Sl. No. {{$group->id.$customer->group_customer_id}}
                </p> -->
                <p>
                    Amount: <span>{{$group->principal_amount}}</span>
                </p>
               
                <p>
                    Date: <span>{{date("d-m-Y",strtotime($group->start_date))}}</span>
                </p>

                <p>
                    Time Period: <span>{{$group->time_line}} days</span>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="image">
                
            </div>
        </div>
        
    </div>
    <hr>
    <table cellpadding="3" cellspacing="0" border="1" style="width:100%">
        <thead>
            <tr>
                <th style="width: 50px;">No.</th>
                <th>Date</th>
                <!-- <th style="width:150px;">Principal Amount Begining At the month</th> -->
                <th style="width:80px;">Principal Repayment</th>
                <th>Interest</th>
                <th style="width:80px;">Principal Payment</th>   

                <th>EMI</th>
                <th>Signature</th>   
                <th>LIR No.</th>   
                <th>Remarks</th>   
                <th>Penalty</th>   
            </tr>
        </thead>
        <tbody>
            @foreach($group->group_dates as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->emi_date}}</td>
                    <td>{{$item->principal_repayment}}</td>

                    <!-- <td>{{$item->start_m_principal}}</td> -->
                    <td>{{$item->interest_payment}}</td>

                    <td>{{$item->principal_payment}}</td> 
                    <td>{{$item->emi_amount}}</td>

                    <td></td>   
                    <td></td> 
                    <td></td> 
                    <td></td> 
                </tr>
            @endforeach
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td>{{$total_int_amount}}</td>
                <td></td>   
                <td>{{$total_amount}}</td> 
                <td></td>   
                <td></td> 
            </tr>
        </tbody>
    </table>
    <div class="page-break"></div>
    <div>
        <h3 class="h-font">नियम और शर्तें</h3>
        <ol>
            <li>महालक्ष्मी माइक्रो फाइनेंस अपने कार्य प्रणाली में पर्यावरण सामाजिक एवं शासन,(S H G ) के निर्धारित मानकों के प्रति समर्पित है</li>
            <li>
                उधारकर्ता का कलेक्शन मीटिंग में, अपने पूर्व निर्धारित मीटिंग स्थान पर समय से आना तथा अपने समस्त लेन देन का हिसाब किताब अपने लोन कार्ड में कंपनी के अधिकारी से दर्ज करवाना अनिवार्य है
            </li>
            <li>
                इस ऋण का उपयोग उधार कर्ता /नॉमिनी/ सह उधारकर्ता करता, ऋण आवेदन के समय बताए गए उद्देश्य में करने के लिए बाध्य है। तथा ऋण उपयोग की जांच समय-समय पर कंपनी के अधिकारियों द्वारा किया जाता रहेगा। रन उद्देश्य के परिवर्तन की दिशा में 30 दिनों के अंदर कंपनी को अवश्य अवगत कराए। किसी भी तरह के गैर कानूनी अथवा उल्लेखित उद्देश्य से अलग कार्य में इस ऋण राशि का उपयोग पूर्णतया वर्जित है ऐसी स्थिति में उधार कर्ता,नॉमिनी,  सह उधार कर्ता अपने इस तरह के अवैध कार्य के लिए पूरी तरह से जिम्मेदार होंगे और महालक्ष्मी माइक्रोफाइनेंस ऐसे कार्य के लिए प्रत्यक्ष या अप्रत्यक्ष रूप से कोई जिम्मेदारी नहीं लेती है।
            </li>
            <li>
                ऋण का वितरण कंपनी द्वारा उधार कर्ता के खाते में ही किया गया है । अगर उधार कर्ता अपने खाते से पैसे निकालकर किसी अन्य व्यक्ति को देते हैं। तो यह ऋण शर्तों का उल्लंघन है तथा कंपनी अपने पूरे ऋण को ब्याज सहित तत्काल भरपाई के लिए उधार कर्ता/नॉमिनी/ सह उधार कर्ता को बाध्य कर सकती है। उधार कर्ता/नॉमिनी से उधार कर्ता के इस प्रकार के व्यक्तिगत लेनदेन के लिए या इस ऋण समझौते के दायरे से बाहर है ऋण राशियों के अन अधिकृत उपयोग के संबंध में कंपनी बिल्कुल अनुमति नहीं देती और न ही किसी प्रकार से जिम्मेदार है संपूर्ण ऋण राशि के भुगतान के लिए उधार कर्ता स्वयं जिम्मेदार है
            </li>
            <li>
                भारतीय रिजर्व बैंक के निर्देशानुसार उधार कर्ता तथा नॉमिनी,सह उधार कर्ता तथा कमाने वाले अविवाहित बच्चों की जानकारी क्रेडिट ब्यूरो के साथ साझा की जाती है किसी भी तरह की ऋण भुगतान में देरी अथवा चूक की दिशा में, क्रेडिट स्कोर खराब होता है तथा ऐसे मामले में उधार कर्ता, नॉमिनी, सह उधार कर्ता को वित्तीय संस्थाओं बैंकों से लेनदेन करने में काफी दिक्कतों का सामना करना पड़ता है। तथा इसके अलावा उधार कर्ता नॉमिनी सह उधार कर्ता भविष्य के ऋणों के लिए अपात्र हो सकते हैं
            </li>
            <li>
                उधार कर्ता सामूहिक दायित्व समूह का हिस्सा है। तथा उधार कर्ता ने समूह का गठन अपने जान पहचान तथा भरोसेमंद सदस्यों के साथ बिना किसी लालच/ दबाव के किया है। अतः चूक की दशा में उधार कर्ता अपने ऋण के साथ-साथ चूक करने वाले समूह के अन्य सदस्यों के भी किस्तों की अदायेगी के लिए जिम्मेदार है। उधार कर्ता इस समूह की अवधारणाओं को समझता है और वचन देता है। की किसी भी गैर भरोसेमंद वह बेईमान व्यक्ति का अपने समूह में  नहीं जोड़ेगा अन्यथा उधार कर्ता को भी असुविधा का सामना करना पड़ सकता है
            </li>
            <li>किस्तों का भुगतान आगे पीछे करने पर ब्याज घट बढ़ सकता है ऋण की पात्रता के लिए कंपनी के किसी अन्य उत्पाद को खरीदने  की कोई बाध्यता नहीं है</li>
            <li>ऋण के लिए किसी भी तरह की प्रीतिभूति नहीं ली जाती है। अवकाश के दिनो में किस्त आपसी सहमति के आधार पर निर्धारित दिन पर जमा किए जाएंगी</li>
            <li>आपके ग्रामीण एरिया में कुछ स्वार्थी तत्व व सूदखोर ऋण माफी की अफवाह फैला कर, सदस्यों का क्रेडिट स्कोर रिकॉर्ड खराब करवाने का प्रयास करते हैं, ताकि वह अपने भुगतान में चूक कर लें, और उनका क्रेडिट स्कोर खराब हो जाए।  इसका मतलब यह है कि वह भविष्य के ऋण के लिए अपात्र हो जाते हैं। और फिर साहूकारों पर निर्भर रहना पड़ता है अतः आपसे गुजारिश है कि ऐसे शरारती तत्वों से सावधान रहे। और अपने ऋण का भुगतान समय पर करें। ताकि आपका भविष्य में क्रेडिट स्कोर सही चलता रहे</li>
            <li>यदि किसी सदस्य या उसके परिवार का कोई सगा संबंधी घूस व कमिशन , दलाली लेते पाया जाता है तो ऐसी स्थिति में उसे उधार कर्ता से पूरे ऋण राशि वापस ले ली जाएगी</li>
            <li>यदि उधार कर्ता तथ्यों और अन्य ऋण की  जानकारी को छुपा कर अपनी क्षमता या सीमा से अधिक ऋण लेने का प्रयास करती है तो इसके लिए महालक्ष्मी माइक्रोफाइनेंस जिम्मेदार नहीं होगी</li>
            <li>कठोर ऋण अनुशासन आपकी साख को बढ़ाता है। अतः उधार कर्ता अपने ऋण का सही उपयोग करें जिससे उधार कर्ता ऋण अदाएगी में असुविधा न हो।</li>
            <li>कंपनी भारतीय रिजर्व बैंक द्वारा बताए गए माइक्रोफाइनेंस दिशा निर्देशों के तहत कार्य करने के लिए प्रतिबद्ध है।</li>
            <li>मोबाइल डिजिटल बैंकिंग से जुड़े हुए सदस्य अपना एम पिन पासवर्ड, संभाल कर रखें।  तथा अपना पासवर्ड किसी भी कंपनी या अन्य व्यक्ति कर्मचारी अधिकारी से साझा ना करें विशेष परिस्थिति में सहायता के लिए उधार कर्ता बैंक के कस्टमर केयर नंबर पर संपर्क करें।</li>
            <li>ऋण के पूर्व अथवा विलंबित भुगतान के लिए किसी भी प्रकार का अतिरिक्त शुल्क नहीं लिया जाता ।</li>
            <li>महालक्ष्मी माइक्रोफाइनेंस एक पारदर्शी तथा जिम्मेदार समाज के निर्माण के लिए प्रतिबंध है अतः अपने बच्चों को नैतिक मूल्यों पर आधारित शिक्षा के लिए प्रेरित करें तथा खुद परिवार के किसी भी सदस्य को नशे की लत से मुक्त रखें</li>
            <li>यदि कोई सदस्य अपनी किस्त अग्रिम जमा करवाना चाहती है तो उन्हें यह क़िस्त शाखा कार्यलय में होगी | </li>       
            <li>यदि कोई सदस्य समय पूर्व भुगतान करना चाहती है तो जाकर उन्हें बकायामूल धन तथा पिछली क़िस्त की तिथि से पुर्ण भुगतान की तिथि का ब्याज देना होगा |     </li>                        
            <li>सदस्य को शाखा प्रबंधक द्वारा निर्धारित समय दिन एव दिनक को समहू की सभी बैठकों में उपस्तिथ रहना होगा</li>
            <li>महालक्ष्मी  माइक्रो फाइनेंसकर्मचारियों के अनुचित व्यवहार रोकने और समय पर शिकायत निवारण के लिए जवाबदेह होगी | </li>      
                                                                                                                                                                                                                                                                     
            <li>नियमोंके उललघने में दिए ऋण की वसूली तब तक सथगित की जाएगी जब तक सभी पूर्ण मोजुदा ऋण को पूरी तरह से चुकाया नहीं जाता |        
            </li>                                                                                                                                  
            <li>महालक्ष्मी  माइक्रो फाइनेंस का उद्देश्य लोगो को गरीबी से उभारना व समाज  समानता लाना है|</li>

        </ol>
    </div>
    <p class="h-font" style="margin-top:20px;">
        ऋणकर्ता के हस्ताक्षर
    </p>
</body>
</html>