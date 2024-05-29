<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <title>{{\App\CPU\translate('New order received')}}</title>

    <style>

        body {
            background-color: #FFFFFF;
            padding: 0;
            margin: 0;
        }
    </style>

</head>

<body style="background-color: #FFFFFF; padding: 0; margin: 0;">

<table border="0" cellpadding="0" cellspacing="10" height="100%" bgcolor="#FFFFFF" width="100%"
       style="max-width: 650px;" id="bodyTable">

    <tr>

        <td align="center" valign="top">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailContainer"
                   style="font-family:Arial; color: #333333;">

                <!-- Logo -->
                @php($logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                <tr>

                    <td align="left" valign="top" colspan="2"
                        style="border-bottom: 1px solid #CCCCCC; padding-bottom: 10px;">
                        <img alt="" border="0" src="{{url('/').'/storage/app/public/company/'.$logo}}" title=""
                             class="sitelogo" width="60%" style="max-width:250px;"/>
                    </td>

                </tr>

                <!-- Title -->

                <tr>

                    <td align="left" valign="top" colspan="2"
                        style="border-bottom: 1px solid #CCCCCC; padding: 20px 0 10px 0;">
                        <span style="font-size: 18px; font-weight: normal;">{{\App\CPU\translate('Notification mail for new order received')}}</span>
                    </td>

                </tr>

                <!-- Messages -->

                <tr>

                    <td align="left" valign="top" colspan="2" style="padding-top: 10px;">

                       <span style="font-size: 12px; line-height: 1.5; color: #333333;" dir="rtl">

    {{\App\CPU\translate('مرحبًا عزيزي العميل،
    أتمنى أن تكون في صحة جيدة. نحب أن نخبركم بأن لديكم طلبًا جديدًا على موقعنا.
    يرجى زيارة الموقع للتحقق من التفاصيل! كما نرجو منكم التواصل مع عملائكم لتأكيد الطلب وترتيب التسليم.
    إذا كان لديكم أي أسئلة أو استفسارات، فلا تترددوا في التواصل معنا. 0773738310 نحن هنا لخدمتكم.
    شكرًا لتفهمكم وتعاونكم الدائم.
    أطيب التحيات،
    NicheN+ فريق الدعم الفني')}}

    <br/><br/>

    {{\App\CPU\translate('رابط الطلبات')}} :
    <a href="javascript:"> <h3 style="font-weight: 1000">{{$id}}</h3> </a>
    
    <br/><br/>

    {{\App\CPU\translate('if_you_need_help')}}, {{\App\CPU\translate('or_you_have_any_other_questions')}}, {{\App\CPU\translate('feel_free_to_email_us')}}.
    
    <br/><br/>

    {{\App\CPU\translate('From')}} {{$web_config['name']->value}}

</span>


                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>

</html>
