<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seosetting;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Languages;
use App\Models\Translate;
use Validator;
use App\Models\Setting;

class LanguagesController extends Controller
{
    public function index()
    {
        $this->authorize('Languages Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $languages = Languages::withCount(['translates' => function ($query) {
            $query->where('value', null);
        }])->get();
        $data['languages'] = $languages;


        return view('admin.languagues.index')->with($data);
    }

    public function create()
    {
        $this->authorize('Languages Create');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;


        return view('admin.languagues.create')->with($data);
    }

    public function edit($lang)
    {

        $this->authorize('Languages Edit');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $language = Languages::findOrFail($lang);
        $data['language'] = $language;

        return view('admin.languagues.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'languagename' => ['required', 'string', 'max:150'],
            'languagenativename' => ['required', 'string', 'max:150'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                // toastr()->error($error);
            }
            return back()->withInput();
        }

        $language = Languages::findOrFail($id);
        if (!$request->has('is_default')) {
            if ($language->languagecode == setting('default_lang')) {
                return back()->with('error', 'Is Default Language');
            }
        }
        $update = Languages::where('id', $id)->update([
            'languagename' => $request->languagename,
            'languagenativename' => $request->languagenativename,
            'is_rtl' => $request->is_rtl ?  '1' : '0',
        ]);
        if ($update) {
            if ($request->has('is_default')) {
                $data['default_lang'] = removeSpaces($language->languagecode);
                $this->updateSettings($data);
            }
            return redirect()->route('admin.languages.index')->with('success', lang('Updated Successfully', 'alerts'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'languagename' => ['required', 'string', 'max:150'],
            'languagenativename' => ['required', 'string', 'max:150'],
            'languagecode' => ['required', 'string', 'max:10', 'min:2', 'unique:languages'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                // toastr()->error($error);
            }
            return back()->withInput();
        }
        if (!array_key_exists($request->languagecode, languages())) {

            return back()->with('error', lang('Something went wrong please try again', 'alerts'));

        }

        $create = Languages::create([
            'languagename' => $request->languagename,
            'languagenativename' => $request->languagenativename,
            'languagecode' => $request->languagecode,
            'is_rtl' => $request->is_rtl ?  '1' : '0',
        ]);


        if ($create) {
            $translates = Translate::where('lang_code', setting('default_lang'))->get();

            foreach ($translates as $translate) {
                $value = ($request->languagecode == "en") ? $translate->key : null;
                $lang = new Translate();
                $lang->lang_code = $request->languagecode;
                $lang->group_langname = $translate->group_langname;
                $lang->key = $translate->key;
                $lang->value = $value;
                $lang->save();
            }
            if ($request->has('is_default')) {

                $data['default_lang'] = removeSpaces($create->languagecode);
                $this->updateSettings($data);
            }
            return redirect()->route('admin.languages.translate', $create->languagecode)->with('success', lang('Created Successfully', 'alerts'));
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  $lang Language code
     * @param  $group Translate group
     * @return \Illuminate\Http\Response
     */
    public function translate(Request $request, $code, $group = null)
    {
        $this->authorize('Languages Translate');

        $language = Languages::where('languagecode', $code)->firstOrFail();
        if ($request->input('search')) {
            $q = $request->input('search');
            $groups = collect([
                (object) ["group_langname" => "Search results"],
            ]);
            $translates = Translate::where([['lang_code', $language->languagecode], ['key', 'like', '%' . $q . '%']])
                ->OrWhere([['lang_code', $language->languagecode], ['value', 'like', '%' . $q . '%']])
                ->OrWhere([['lang_code', $language->languagecode], ['group_langname', 'like', '%' . $q . '%']])
                ->orderbyDesc('id')
                ->get();
            $active = "Search results";
        } elseif ($request->input('filter')) {
            abort_if($request->input('filter') != 'missing', 404);
            $groups = collect([
                (object) ["group_langname" => "missing translations"],
            ]);
            $translates = Translate::where([['lang_code', $language->languagecode], ['value', null]])->orderby('group_langname')->get();
            $active = "missing translations";
        } else {
            $groups = Translate::where('lang_code', $language->languagecode)->select('group_langname')->groupBy('group_langname')->orderBy('id', 'ASC')->get();
            if ($group != null) {
                $group = str_replace('-', ' ', $group);
                $translates = Translate::where('lang_code', $language->languagecode)->where('group_langname', $group)->get();
                abort_if($translates->count() < 1, 404);
                $active = $group;
            } else {
                $translates = Translate::where('lang_code', $language->languagecode)->where('group_langname', 'general')->get();
                $active = "general";
            }
        }
        $translates_count = Translate::where('lang_code', $language->languagecode)->where('value', null)->count();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;


        return view('admin.languagues.translate', [
            'active' => $active,
            'translates' => $translates,
            'groups' => $groups,
            'language' => $language,
            'translates_count' => $translates_count,
        ])->with($data);
    }


    /**
     * Update the translate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function translateUpdate(Request $request, $id)
    {
        if($request->values == null){
            return back()->with('error', lang('Please select any data to translate', 'alerts'));
        }

        $language = Languages::where('id', $id)->first();
        if ($language == null) {
            // toastr()->error(__('Something went wrong please try again'));
            return back();
        }
        foreach ($request->values as $id => $value) {
            $translation = Translate::where('id', $id)->first();
            if ($translation != null) {
                $translation->value = $value;
                $translation->save();
            }
        }
        // toastr()->success(__('Updated Successfully'));
        return back()->with('success', lang('Updated Successfully', 'alerts'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  $id language ID
     * @return \Illuminate\Http\Response
     */
    public function setDefault($id)
    {
        $language = Languages::find(decrypt($id));

        if ($language != null) {
            if (setting('default_lang') == $language->languagecode) {
                return back()->with('error', lang('Language already marked as default', 'alerts'));
            } else {
                $data['default_lang'] = $language->languagecode;
                $this->updateSettings($data);
                return back()->with('success', lang('Default language has updated Successfully', 'alerts'));
            }
        } else {
            return back()->with('error', lang('language not exists', 'alerts'));
        }
    }


    /**
     *  Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    private function updateSettings($data)
    {

        foreach($data as $key => $val){
        	$setting = Setting::where('key', $key);
        	if( $setting->exists() )
        		$setting->first()->update(['value' => $val]);
        }

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy($language)
    {
        $language = Languages::find($language);

        if ($language->languagecode == setting('default_lang')) {

            return response()->json(['error'=> lang('Default language cannot be deleted', 'alerts')]);
        }

        $language->delete();
        return response()->json(['success'=> lang('Deleted Successfully', 'alerts')]);
    }
}
