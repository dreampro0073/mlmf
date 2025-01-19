<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <!-- <link href="https://fonts.googleapis.com/css?family=Hind:400,700&amp;subset=devanagari,latin-ext" rel="stylesheet"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@200&family=Tiro+Devanagari+Hindi&display=swap" rel="stylesheet">


    <title>MLMF - Shapat Patra</title>
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
            /*font-family: Hind, DejaVu Sans, sans-serif;*/

            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            font-family: 'Tiro Devanagari Hindi', serif;
        }
        ul{
            padding-left: 15px;
        }
        li{
            font-size: 12px;
            margin-bottom:0px;
            line-height: 1.2;
        }
        .page-break {
            page-break-after: always;
        }
        .h-font span{
            font-family: sans-serif;
        }
        .fs-12{
            font-size: 12px;
        }
        .mb-0{
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="row personal-bio" style="height:150px;">
        <div class="col-md-6">
            <img src="{{url('assets/img/logo.png')}}" style="height:60px;width:auto;"> &nbsp;&nbsp;
        </div>
        <div class="col-md-6">
            <div style="text-align: right;">
                @if(isset($customer->joint_photo))
                    <img src="{{url($customer->joint_photo)}}" style="width:200pxpx;height: 150px;object-fit: cover;float: right;">
                    
                @endif
            </div>
        </div>
    </div>
   
    <h4>Date : {{date("d-m-Y",strtotime($group->start_date))}}</h4>
    <div class="row personal-bio">
        <div class="col-md-6">
            <div class="bio">
                <p class="h-font fs-12 mb-0">समूह : <span>{{$group->group_name}}</span></p>
                <p class="fs-12 mb-0">Address : <span>{{$group->village_name}}</span></p>
                <p class="h-font fs-12 mb-0">Customer Id  : <span>{{$customer->unique_id}}</span></p>
                <p class="h-font fs-12 mb-0">ऋणकर्ता का नाम  : <span>{{$customer->name}}</span></p>
                <p class="h-font fs-12 mb-0">Mobile Number  : <span>{{$customer->mobile}}</span></p>
                <p class="fs-12">K Y C :1 -Aadhr Card No:  <span>{{$customer->aadhaar_no}}</span></p>
                <p class="fs-12">K Y C :2 -Pan Card No: <span>{{$customer->pan_no}}</span></p>
                <p class="fs-12">K Y C :3 -Voter Card No: <span>{{$customer->voter_id_no}}</span></p>
                <p class="fs-12">DOB: <span>{{date("d-m-Y",strtotime($customer->dob))}}</span></p>
                <p class="fs-12">{{$customer->c_bank_name}}</span></p>
                <p class="fs-12">{{$customer->ifsc_code}}</span></p>
                <p class="fs-12">{{$customer->ac_no}}</span></p>
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="bio">
                
                <p class="fs-12">
                    Loan Amount: <span>{{$group->principal_amount}}</span>
                </p>
                <p class="h-font fs-12">ऋणकर्ता के पुत्र  का  : <span>{{$customer->guarantor_name}}</span></p>
                <p class="fs-12">K Y C :1 -Aadhr Card No:  <span>{{$customer->guarantor_aadhaar_no}}</span></p>
                <p class="fs-12">K Y C :2 -Pan Card No: <span>{{$customer->guarantor_pan_no}}</span></p>
                <p class="fs-12">K Y C :3 -Voter Card No: <span>{{$customer->guarantor_voter_id_no}}</span></p>

                <p class="fs-12">Mobile No: <span>{{$customer->mobile}}</span></p>
                <p class="fs-12">{{$customer->guarantor_bank_name}}</span></p>
                <p class="fs-12">{{$customer->guarantor_ifsc_code}}</span></p>
                <p class="fs-12">{{$customer->guarantor_ac_no}}</span></p>
                
            </div>
        </div>
       

    </div>
    <hr>

    <div>
        <p class="h-font fs-12">
            चूंकि मानव परिवार के सभी सदस्यों के जन्मजात गौरव और समान
        </p>
        <p class="h-font fs-12">
            इसके द्वारा मै: <span>{{$customer->name}}</span> पत्नी/पुत्र  <span>{{$customer->guarantor_name}}</span> <span>{{$customer->purpose}}</span>  (प्रयोजक) के लिए {{$group->principal_amount}}/ -रुपए के ऋण  हेतू आवेदन करती हूँ| एतद द्वारा ,मै अपनी  इछा से यह ऋण महालक्ष्मी माइक्रो फाइनेंस में आवेदन कर रही हूँ  और पृष्ठ के दूसरी तरफ उल्लेखित वचन बद्धताओ (नियम एव शर्त) पर सहमति दे रही हूँ और निम्लिखित ततपर घोषणाएं करती हू जो सत्य व् सही है |
        </p> 
        <p class="h-font fs-12">
            मैं घोषणा करती हूँ कि कमाई करने वालेसदस्यों की संख्या ,हमारी घरेलू आय  और सभी ऋण स्रोतों के  <span>{{$group->type_name}}</span>  <span>{{$group->emi_amount}}</span> EMI भुगतान के आंकड़े कंपनी द्वारा सही ढंग से दर्ज किये गएहै।

        </p>
        <p class="h-font fs-12">
            मैं इसके द्वारा यह घोषणा करती हूं कि  <span>{{$group->village_name}}</span>  पर स्थित घर मेरे और मेरे परिवार के सदस्यों के स्वामित्व में है |
        </p>
        <p class="h-font fs-12">
            मै .महालक्ष्मी माइक्रो फाइनेंस ने,स्वीकृत/प्राप्त ऋण रकम को <span>{{$group->interest_rate}}</span>% (हासमान शेष विधि) प्रति वर्ष की लागु ब्याज दर के साथ {{$group->no_of_emis}} समान अर्ध मासिक किस्तों (अदायगी की आवृत्ति जो मेरेद्वारा स्वेच्छा से चुनी गई है) मै अदा करने की वचन देती हू|
        </p>
        <p class="h-font fs-12">
            उपयुक्त विषय वस्तु को मेरे निर्दोशो पे भरा गया है और समूह द्वारामुझे और मेरे समूह के सदस्य को हमारी भाषा मै पढ़कर सुना दिया व् मुझे समझा दिया गया है।

        </p>
        <p class="h-font fs-12">
            मै ऋणकर्ता महालक्ष्मी माइक्रो फाइनेंस कंपनियां के निम्लिखित उत्पाद खरीद रही हूँ
        </p>
        <p style="margin:5px 0;">
            _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _  _ _ 
        </p>

        <p class="h-font fs-12 ">
            श्रीमती  {{$customer->name}}  पत्नी/पुत्र   {{$customer->guarantor_name}}  निवासी  <span>{{$group->village_name}}</span>  द्वारा आवेदन पर और {{$group->principal_amount}}रु. के प्रकरण शुल्क  {{$group->processing_fee}} /-के सेवा कर इव् ˈक्रेडिट् RS- <span>{{$group->insurance_fee}}</span>/-बीमा प्रीमियर का भुगतान करने के अध्यादिन महालक्ष्मी  माइक्रो फाइनेंस द्वारा {{$group->principal_amount}} रु. का ऋण स्वीकृत किया जाता है| ऋण की रकम {{$group->interest_rate}} % (हासमान शेष विधि) प्रति वर्ष की लागु ब्याज दर के साथ {{$group->no_of_emis}} समान अर्धमासिक किस्तों मै प्रति देय है| प्रभावी वार्षिक प्रतिशत दर {{$group->no_of_emis}} है| स्वीकृत राशि  {{$group->principal_amount}}/-रु.
        </p>
        <p class="h-font fs-12 ">
            दिनांक: {{$group->start_date}}
        </p>
        <p class="h-font fs-12 ">
            ब्रांच मैनेजर के हस्ताक्षर/कर्मचारी आईडी:ऋण की निबंधन इव् शर्ते
        </p>
        <p class="h-font fs-12 ">
            इसके द्वारा मुझे  {{$customer->name}}  पत्नी  पुत्र  {{$customer->guarantor_name}}  निवासी  <span>{{$group->village_name}}</span> गांव, जिसका महालक्ष्मी माइक्रो फाइनेंस क o का है|
        </p>
        <p class="h-font fs-12 ">
            पृष्ठ 1पर  उल्लिखित ऋण निबंधनो इव् शर्तो को मेरी देशी भाषा मै समझा दिया गया है और मै अपनी  स्वतंत्र इच्छा और चाह के उक्त ऋण प्राप्त करने के लिए उक्त निबंधन एवं शर्तो  पर सहमत हू| 

        </p>
        <p class="h-font fs-12 " style="text-align: right;margin-top: 45px;">
            ✕ ऋणकर्ता के हस्ताक्षर
        </p>
    </div>
 
        
    <div class="page-break"></div>

    <div>
        <p class="h-font fs-12 mb-0">
            मांग प्रतिज्ञा पत्र
        </p>

        <p class="h-font fs-12 mb-0">
            मांगने पर मै/हम  <span>{{$customer->name}}</span>  पुत्र/WO/पुत्र  {{$customer->guarantor_name}}  नीचे हस्ताक्षरकर्ता संयुक्त रूप से और अलग-अलग प्राप्तकिये गए मूल्य के लिए  <span>{{$group->principal_amount}}</span>/- रु. को {{$group->interest_rate}} प्रतिशक(घटता हुआ बकाया विधि) प्रति वर्ष की दर पर ब्याजसहित महालक्ष्मी माइक्रो फाइनेंस  अथवा आदेशनुसार को अदा करने की वचनदेती/देता है| भुगतान प्रस्तुति और नोट की नोटीग और प्रतिवाद को इसके द्वारा बिना शर्ते माफ़ किया जाता है|
        </p>
        <p class="h-font fs-12">
            1 ____________
        </p>
        <p class="h-font fs-12">
            2 ______________पति/पुत्र अथवा पुत्री 18 से ऊपर/समूह लीडर
        </p>
        <p class="h-font fs-12">
            3 ______________ 
        </p>
        <div class="row">
            <div class="col-md-6">
                <div class="bio">
                    <p class="h-font fs-12">दिनांक:</p>
                    <p class="h-font fs-12">
                        हस्ताक्षर द्वारा सत्यप्रित
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bio" style="text-align: right;">
                    <p class="h-font fs-12" style="text-align:right;">
                        10रु.का रेवेन्यु स्टैम्प
        
                    </p>
                    <p class="h-font fs-12" style="text-align:right;margin-top: 20px;">
                        ✕  ऋणकर्ता के हस्ताक्षर
        
                    </p>
                </div>
            </div>
        </div>
       
        
    </div>
    <div class="page-break"></div>

    <div>
        <ol>
            <li>सदस्य को ऋण आवेदन पत्र में दी गई जानकारी को पूर्णतः समज लेना चाहिए तथा ऋण द्वारा ली गई राशि का उपयोग पूर्व निर्धारित मानदंड के अनुसारकरना चाइये | </li>  
            <li>ऋण की राशि में ब्याज दर ,प्रक्रिया शुल्क और बीमा प्रीमियर केवल मेम्बर का डेथ कलेम ही  होगा इसमें किसी भी प्रकार का बीमारी का बीमा नहीं  होगा | इसके अतिरिक्त जमानतदार , उधारकर्ता से पूरा पैसा लिया जायेगा  </li>   

            <li>सदस्य को ऋण उपयोग किसी भी गैरकानूनी कार्य के लिए नहीं करना चाहिए |    </li>           
            <li>सदस्य की उम्र कर्ज के लिए आवेदन करते समय 18-50के बीच होना चाहिए | </li>       
            <li>सदस्य की वार्षिक घरलू आमदनी ग्रामीण इलाके में 3,00,000 से अधिक और गैर ग्रामीण इलाके 3,00,000 से अधिक नहीं होनी चाहिए |  </li> 
            <li>प्रस्ताविक कर्ज समेत सभी स्त्रोतों से सदस्य का कुल बकाया कर्ज 1,00,000/-रूपए से अधिक नहीं होना चाहिए </li> 
            <li>सदस्य के महालक्ष्मी माइक्रो फाइनेंस के अलावा किसी एक से अधिक एम एफ आइ/ एस एचजी/ जे एल जी किसी भी वित्तीय संस्था के पास सक्रीय कर्ज खाता नहीं होनाचाहिए और किसी एक /SHG समूह से ज्यादा का सदस्य नहीं होना चाहिए |     </li>        
            <li>सदस्य का महालक्ष्मी माइक्रो फाइनेंससमेत किसी भी एम एफ आइ/बैंक/एस एच जी/जकिसी भी वित्तीय संस्था के पास बीती मियाद की राशि नहीं होनी चाहिए|         </li> 
            <li>सदस्य के पास मान्य के वाई ई सी दस्तावेज होना चाहिए |   </li>    
            <li>कर्ज की राशि को कर्ज वापसी की अवधि और तय समय के अनुसार ब्याज के साथ किस्तों में चुकाया जाता है|</li>

            <li>कर्ज की राशि को महालक्ष्मी माइक्रो फाइनेंस  के संयुक्त दायित्व समूह के तोर पर विस्तारित किया जायेगा |</li>           

            <li>ग्राहक को भारतीय नागरिक होना चाहिए जिसके खिलाफ कोई आपराधिकमामले नहीं हो और यह किसी भी प्रतिबंधित संगठन का नहीं होना चाहिए |</li>
            <li>ग्राहक को बिना किसी चूक के समूह के सभी बैठक में आना चाहिएकर्ज का उपयोग केवल सहमति उद्देश्य के लिए करना चाहिए,बिना किसी चूक केकर्ज की किश्त चुकानी चाहिए समूह सदस्य को कोई समस्या होने पर उनकी मददकरना चाहिए और अपने कर्ज वापसी क्षमता से ज्यादा कर्ज नहीं लेना चाहिए |</li>


            <li>कर्ज अदायगी सरणी के अनुसार कर्ज की राशि और /ब्याज को चुकानेके लिए ग्राहक पृथक रूप से और समूह के अन्य सदस्यों के साथ संयुक्त रूप सेजिम्मेदार होगा | </li>   
            <li>ऋण के नियम और शर्तों किसी भी बदलाव की सूचना उधारकर्ता कोस्थानीय भाषा मे दी जाएगी और यह बदलाव सिर्फ भावी रूप से प्रभाव में आयेगी |  </li>
            <li>महालक्ष्मी माइक्रो फाइनेंसयह विश्वास दिलाती है की वह उधारकर्ता के डाटा का पूरा सम्मान करेगा |     </li>


            <li>सभी कर्ज को क्रेडिट ब्यूरो की जांच के अधीन रखा जाएगा औरइसीलिए डेटा की क्रेडिट ब्यूरो एजेंसी के साथ साझा किया जायेगा |</li>
            <li>देरी से किये गए भुगतान के लिए कोई भुगतान नहीं लिया जायेगा|</li>
            <li>कर्ज को समय से पाहिले खत्म करने का आंशिक रूप से चुकानी पर कोई शुल्क लगाया जाता है |</li>

            <li>उधारकर्ता से कोई शुल्क जमा /लाभांश एकत्रित नहीं किया जायेगा | </li>
            <li>सदस्य को किस्त का भुगतान केवल समूह के बैठक में उपस्थित रहकर करना चाहिए| </li>                                        

            <li>ऋण की प्राप्तता एम एफ आइ या अन्य किसी कंपनी द्वारा दिए जाने वाले उत्पाद या सेवा से जुड़ी नहीं है|   </li>                             

            <li>सामान्य रूप से वितरण 1 महीने के भीतर किया जायेगा | लेकिन यह विभिन्न परिस्थितियों पर करता है जैसे(A)बाजार की स्तिथि (b) ग्राहक का उधारी इतिहास (c) कंपनी की वित्तीय स्थिति (d) अन्य पर्यावरणिक कारनउपर दिये गये बिन्दुओं की वजह से ऋण किसी भी समय रद किया जा सकता है।    </li>                                                                                                                                            
            <li>हर कर्ज पर एक क़िस्त के मॉरटॉरीअम की सुविधा होगी |  </li>                                          

            <li>महालक्ष्मी  माइक्रो फाइनेंस  पारदर्शिता और निष्पन्न उधार प्रथाओं के लिए के लिए प्रतिबद्ध है| </li>                                                                        

            <li>महालक्ष्मी  माइक्रो फाइनेंसकर्मचारियों के अनुचित व्यवहार रोकने और समय पर शिकायत निवारण के लिए जवाबदेह होगी   </li>      
                                                                                                                                                                                                                                                                             
            <li>नियमोंके उललघने में दिए ऋण की वसूली तब तक सथगित की जाएगी जब तक सभी पूर्ण मोजुदा ऋण को पूरी तरह से चुकाया नहीं जाता | </li>                                                                                                                                
            <li>महालक्ष्मी  माइक्रो फाइनेंस का उद्देश्य लोगो को गरीबी से उभारना व समाज  समानता लाना है|</li>
            <li>महालक्ष्मी  माइक्रो फाइनेंसकर्मचारियों के अनुचित व्यवहार रोकने और समय पर शिकायत निवारण के लिए जवाबदेह होगी | </li>      
                                                                                                                                                                                                                                                                     
            <li>नियमोंके उललघने में दिए ऋण की वसूली तब तक सथगित की जाएगी जब तक सभी पूर्ण मोजुदा ऋण को पूरी तरह से चुकाया नहीं जाता |        
            </li>                                                                                                                                  
            <li>महालक्ष्मी  माइक्रो फाइनेंस का उद्देश्य लोगो को गरीबी से उभारना व समाज  समानता लाना है|</li> 
        </ol>
        <p class="h-font fs-12" style="margin-top:45px; text-align: right;">
            ऋणकर्ता के हस्ताक्षर
        </p>
    </div>

</body>
</html>