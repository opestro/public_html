@extends('theme-views.layouts.app')

@section('title',\App\CPU\translate('Congratulation!'))

@push('css_or_js')



    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')
    <div class="container for-container rtl __inlini-51">
        <h2 class="text-center mt-3 headerTitle">{{\App\CPU\translate('Congratulations')}}</h2>
        
@if(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
        
        <div class="container" style="margin-top: 60px; margin-bottom: 60px; display: flex; align-items: center; justify-content: center;">
       
        <p>
       Nous vous remercions d'avoir soumis votre demande pour créer un magasin. Nous avons bien reçu votre demande. Pour procéder à l'approbation de votre magasin et débuter votre parcours avec nous, veuillez d'abord effectuer le paiement de l'adhésion , le paiement total est {{ $total_price }} DA.
       
        Remplissez le <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;">FORMULAIRE DE LA 2EME ETAPE</a> en saisissant les informations nécessaires et en fournissant la preuve de paiement. Si vous rencontrez le moindre problème, veuillez contacter notre équipe de support sur WhatsApp au 0773738310.
       </p>

        
    </div>
@elseif(Session::get('direction') === "ltr" && app()->getLocale() === "en")
   
    <div class="container" style="margin-top: 20px; margin-bottom: 60px; display: flex; align-items: center; justify-content: center;">
      
        <p>
          Thank you for submitting your request to create a store. We have received your request. To proceed with the approval of your store and start your journey with us, please make the membership payment first and the total payment is {{ $total_price }} DA.
             
          Fill out the <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;">SECOND STEP FORM</a> by entering the required information and providing proof of payment. If you encounter any issues, please contact our support team on WhatsApp at 0773738310.
      </p>

    </div>

@elseif(Session::get('direction') === "rtl" && app()->getLocale() === "dz")
    <div class="container" style="margin-top: 20px; margin-bottom: 60px; display: flex; align-items: center; justify-content: center;">
        
      <p>
        شكراً لتقديم طلب إنشاء متجركم. لقد تلقينا الطلب بنجاح. للمضي قدماً في مراجعة طلبكم وبدء رحلتكم معنا، نرجوا منكم إجراء دفع العضوية أولاً. والمبلغ الإجمالي هو {{ $total_price }} دج.
    
        يرجى ملء النموذج الخاص بالخطوة الثانية عبر الرابط التالي
    
        في هذا النموذج، قم بإدخال المعلومات المطلوبة وتقديم إثبات الدفع. إذا واجهتم أي مشكلة، يرجى التواصل مع فريق الدعم لدينا على واتساب على الرقم 0773738310.
        <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;"> الاستمارة هنا </a>
    </p>

    </div>
@endif  

 </div>
@endsection
