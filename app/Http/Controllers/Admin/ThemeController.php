<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{

    public function theme_setup(Request $request)
    {
        Helpers::setEnvironmentValue('WEB_THEME', $request['theme_id']);
        Toastr::success('Web theme updated successfully!');
        return back();
    }

    public function theme_index()
    {
        $scan = scandir(base_path('resources/themes'));
        $themes_folders = array_diff($scan, ['.', '..']);

        $themes = [];
        foreach ($themes_folders as $folder){
            $info = file_exists(base_path('resources/themes/'.$folder.'/public/addon/info.php')) ? include(base_path('resources/themes/'.$folder.'/public/addon/info.php')) : [];
            $themes[$folder] = $info;
        }

        return view('admin-views.business-settings.theme-setup', compact('themes'));
    }

    public function theme_install(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme_upload' => 'required|mimes:zip'
        ]);

        if ($validator->errors()->count() > 0) {
            $error = Helpers::error_processor($validator);
            return response()->json(['status' => 'error', 'message' => $error[0]['message']]);
        }

        $file = $request->file('theme_upload');
        $filename = $file->getClientOriginalName();
        $tempPath = $file->storeAs('temp', $filename);

        $zip = new \ZipArchive();
        if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {
            // Extract the contents to a directory
            $extractPath = base_path('resources/themes');
            $zip->extractTo($extractPath);
            $zip->close();
            if(File::exists($extractPath.'/'.explode('.', $filename)[0].'/public/addon/info.php')){
                File::chmod($extractPath.'/'.explode('.', $filename)[0].'/public/addon', 0777);
                Toastr::success(\App\CPU\translate('theme_upload_successfully!'));
                $status = 'success';
                $message = \App\CPU\translate('theme_upload_successfully!');
            }else{
                File::deleteDirectory($extractPath.'/'.explode('.zip', $filename)[0]);
                $status = 'error';
                $message = \App\CPU\translate('invalid_theme!');
            }
        }else{
            $status = 'error';
            $message = \App\CPU\translate('theme_upload_fail!');
        }

        Storage::delete($tempPath);

        return response()->json([
            'status' => $status,
            'message'=> $message
        ]);
    }

    public function publish(Request $request)
    {
        $theme_info = include('resources/themes/'.$request['theme'].'/public/addon/info.php');
        if ($request['theme'] != 'default' && (empty($theme_info['purchase_code']) || empty($theme_info['username']) || $theme_info['is_active'] == '0')) {
            $theme = $request['theme'];
            return response()->json([
                'flag' => 'inactive',
                'view' => view('admin-views.business-settings.partials.theme-activate-modal-data', compact('theme_info', 'theme'))->render(),
            ]);
        }

        Helpers::setEnvironmentValue('WEB_THEME', $request['theme']);
    }

    public function activation(Request $request): Redirector|RedirectResponse|Application
    {
        $remove = ["http://", "https://", "www."];
        $url = str_replace($remove, "", url('/'));
        $full_data = include('resources/themes/'.$request['theme'].'/public/addon/info.php');

        $post = [
            base64_decode('dXNlcm5hbWU=') => $request['username'],
            base64_decode('cHVyY2hhc2Vfa2V5') => $request['purchase_code'],
            base64_decode('ZG9tYWlu') => $url,
        ];

        $response = Http::post(base64_decode('aHR0cHM6Ly9jaGVjay42YW10ZWNoLmNvbS9hcGkvdjEvZG9tYWluLXJlZ2lzdGVy'), $post)->json();
        $status = base64_decode($response['active']);

        if((int)base64_decode($status)){
            $full_data['is_active'] = 1;
            $full_data['username'] = $request['username'];
            $full_data['purchase_code'] = $request['purchase_code'];
            $str = "<?php return " . var_export($full_data, true) . ";";
            file_put_contents(base_path('resources/themes/'.$request['theme'].'/public/addon/info.php'), $str);

            Toastr::success(translate('activated_successfully'));
        }else{
            Toastr::error(translate('activation failed'));
        }
        return back();
    }

    public function delete_theme(Request $request){
        $theme = $request->theme;

        if(theme_root_path() == $theme){
            return response()->json([
                'status' => 'error',
                'message'=> translate("can't_delete_the_active_theme")
            ]);
        }
        $full_path = base_path('resources/themes/'.$theme);

        if(File::deleteDirectory($full_path)){
            return response()->json([
                'status' => 'success',
                'message'=> translate('theme_delete_successfully')
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message'=> translate('theme_delete_fail')
            ]);
        }

    }

    function getDirectories(string $path): array
    {
        $directories = [];
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item == '..' || $item == '.')
                continue;
            if (is_dir($path . '/' . $item))
                $directories[] = $item;
        }
        return $directories;
    }
}
