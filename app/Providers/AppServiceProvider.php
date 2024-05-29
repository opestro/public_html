<?php

namespace App\Providers;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Model\Banner;
use App\Model\BusinessSetting;
use App\Model\Category;
use App\Model\Currency;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\SocialMedia;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Model\Shop;
use App\Model\Brand;
use App\Model\Product;
use App\Model\Tag;
use Illuminate\Support\Facades\Auth;

ini_set('memory_limit', -1);
ini_set('upload_max_filesize', '180M');
ini_set('post_max_size', '200M');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Amirami\Localizator\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Paginator::useBootstrap();

        try {
            if (Schema::hasTable('business_settings')) {

                $web = BusinessSetting::all();
                $settings = Helpers::get_settings($web, 'colors');
                $data = json_decode($settings['value'], true);

                $web_config = [
                    'primary_color' => $data['primary'],
                    'secondary_color' => $data['secondary'],
                    'primary_color_light' => isset($data['primary_light']) ? $data['primary_light'] : '',
                    'name' => Helpers::get_settings($web, 'company_name'),
                    'phone' => Helpers::get_settings($web, 'company_phone'),
                    'web_logo' => Helpers::get_settings($web, 'company_web_logo'),
                    'mob_logo' => Helpers::get_settings($web, 'company_mobile_logo'),
                    'fav_icon' => Helpers::get_settings($web, 'company_fav_icon'),
                    'email' => Helpers::get_settings($web, 'company_email'),
                    'about' => Helpers::get_settings($web, 'about_us'),
                    'footer_logo' => Helpers::get_settings($web, 'company_footer_logo'),
                    'copyright_text' => Helpers::get_settings($web, 'company_copyright_text'),
                    'decimal_point_settings' => !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0,
                    'seller_registration' => BusinessSetting::where(['type' => 'seller_registration'])->first()->value,
                    'wallet_status' => Helpers::get_business_settings('wallet_status'),
                    'loyalty_point_status' => Helpers::get_business_settings('loyalty_point_status'),
                ];

                if (!Request::is('admin') && !Request::is('admin/*') && !Request::is('seller/*')) {
                    $flash_deals = FlashDeal::with(['products.product.reviews', 'products.product' => function ($query) {
                        $query->active();
                    }])->where(['deal_type' => 'flash_deal', 'status' => 1])
                        ->whereDate('start_date', '<=', date('Y-m-d'))
                        ->whereDate('end_date', '>=', date('Y-m-d'))
                        ->first();

                    $featured_deals = Product::active()
                        ->with([
                            'seller.shop',
                            'flash_deal_product.feature_deal',
                            'flash_deal_product.flash_deal' => function ($query) {
                                return $query->whereDate('start_date', '<=', date('Y-m-d'))
                                    ->whereDate('end_date', '>=', date('Y-m-d'));
                            }
                        ])
                        ->whereHas('flash_deal_product.feature_deal', function ($query) {
                            $query->whereDate('start_date', '<=', date('Y-m-d'))
                                ->whereDate('end_date', '>=', date('Y-m-d'));
                        })
                        ->get();

                    if ($featured_deals) {
                        foreach ($featured_deals as $product) {
                            $flash_deal_status = 0;
                            $flash_deal_end_date = 0;

                            foreach ($product->flash_deal_product as $deal) {
                                $flash_deal_status = $deal->flash_deal ? 1 : $flash_deal_status;
                                $flash_deal_end_date = isset($deal->flash_deal->end_date) ? date('Y-m-d H:i:s', strtotime($deal->flash_deal->end_date)) : $flash_deal_end_date;
                            }

                            $product['flash_deal_status'] = $flash_deal_status;
                            $product['flash_deal_end_date'] = $flash_deal_end_date;
                        }
                    }

                    $shops = Shop::whereHas('seller', function ($query) {
                        return $query->approved();
                    })->take(9)->get();

                    $recaptcha = Helpers::get_business_settings('recaptcha');
                    $socials_login = Helpers::get_business_settings('social_login');
                    $social_login_text = false;
                    foreach ($socials_login as $socialLoginService) {
                        if (isset($socialLoginService) && $socialLoginService['status'] == true) {
                            $social_login_text = true;
                        }
                    }

                    $popup_banner = Banner::inRandomOrder()->where(['published' => 1, 'banner_type' => 'Popup Banner'])->first();

                    $header_banner = Banner::where('banner_type', 'Header Banner')->where('published', 1)->latest()->first();

                    $payments_name_list = ['ssl_commerz_payment', 'paypal', 'stripe', 'razor_pay', 'senang_pay',
                        'paytabs', 'paystack', 'paymob_accept', 'fawry_pay', 'mercadopago', 'liqpay', 'flutterwave',
                        'paytm', 'bkash'];
                    $payments_list = BusinessSetting::whereIn('type', $payments_name_list)->whereJsonContains('value->status', '1')->pluck('type');

                    $web_config += [
                        'cookie_setting' => Helpers::get_settings($web, 'cookie_setting'),
                        'announcement' => Helpers::get_business_settings('announcement'),
                        'currency_model' => Helpers::get_business_settings('currency_model'),
                        'currencies' => Currency::where('status', 1)->get(),
                        'main_categories' => Category::with(['childes.childes'])->where('position', 0)->priority()->get(),
                        'business_mode' => Helpers::get_business_settings('business_mode'),
                        'social_media' => SocialMedia::where('active_status', 1)->get(),
                        'ios' => Helpers::get_business_settings('download_app_apple_stroe'),
                        'android' => Helpers::get_business_settings('download_app_google_stroe'),
                        'refund_policy' => Helpers::get_business_settings('refund-policy'),
                        'return_policy' => Helpers::get_business_settings('return-policy'),
                        'cancellation_policy' => Helpers::get_business_settings('cancellation-policy'),
                        'flash_deals' => $flash_deals,
                        'featured_deals' => $featured_deals,
                        'shops' => $shops,
                        'brand_setting' => Helpers::get_business_settings('product_brand'),
                        'discount_product' => Product::with(['reviews'])->active()->where('discount', '!=', 0)->count(),
                        'recaptcha' => $recaptcha,
                        'socials_login' => $socials_login,
                        'social_login_text' => $social_login_text,
                        'popup_banner' => $popup_banner,
                        'header_banner' => $header_banner,
                        'payments_list' => $payments_list, // fashion_theme
                    ];

                    if (theme_root_path() == "theme_fashion") {

                        $features_section = [
                            'features_section_top' => BusinessSetting::where('type', 'features_section_top')->first() ? BusinessSetting::where('type', 'features_section_top')->first()->value : [],
                            'features_section_middle' => BusinessSetting::where('type', 'features_section_middle')->first() ? BusinessSetting::where('type', 'features_section_middle')->first()->value : [],
                            'features_section_bottom' => BusinessSetting::where('type', 'features_section_bottom')->first() ? BusinessSetting::where('type', 'features_section_bottom')->first()->value : [],
                        ];

                        // dd($features_section);
                        $tags = Tag::orderBy('visit_count', 'desc')->take(15)->get();

                        $total_discount_products = Product::with(['reviews'])->active()->where('discount', '!=', 0)->count();

                        $web_config += [
                            'tags' => $tags,
                            'features_section' => $features_section,
                            'total_discount_products' => $total_discount_products,
                        ];
                    }
                }

                //language
                $language = BusinessSetting::where('type', 'language')->first();

                //currency
                \App\CPU\Helpers::currency_load();

                View::share(['web_config' => $web_config, 'language' => $language]);

                Schema::defaultStringLength(191);
            }
        }catch (\Exception $exception){

        }

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */

        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

    }
}
