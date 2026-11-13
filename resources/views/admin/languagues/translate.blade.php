@extends('layouts.adminmaster')

@section('content')
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Translation') }} /
                    {{ $language->languagename }}</span></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- row -->
    <div class="col-xl-12">
        <div class="card buttons-translate">
            <div class="card-header p-3 px-5">
                <h4 class="card-title mb-0">{{ lang('Translate') }}</h4>
                <div class="card-options">
                    <form action="{{ request()->url() }}" method="GET">
                        <div class="input-group translate-input">
                            <input type="text" class="form-control" name="search" placeholder="{{ lang('Search') }}"
                                value="{{ request()->input('search') ?? '' }}">
                            <button type="submit" class="btn border"><i class="fe fe-search"></i></button>
                            <button type="button" class="btn border dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ lang('Filters') }} <i class="fa fa-angle-down ms-1"></i></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"
                                        href="{{ request()->url() . '?filter=missing' }}">{{ lang('Missing Translates') }}</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.languages.translate', $language->languagecode) }}">{{ lang('All Translates') }}</a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0 pt-4">
                @if ($translates_count > 0)
                    <div class="px-5 mb-4">
                        <div class="alert alert-light-warning d-flex px-5 py-2" role="alert">
                            <div class="me-3">
                                <i class="fe fe-alert-circle fs-30"></i>
                            </div>
                            <div>
                                <h4 class="alert-heading fs-15 font-weight-semibold mb-0">
                                    {{ lang('This language is incomplete') }}</h4>
                                <p class="fs-13 mb-0">{{ $translates_count }}
                                    {{ lang('translations are missing. You can') }}
                                    <a
                                        href="{{ request()->url() . '?filter=missing' }}">{{ lang('filter this page') }}</a>
                                    {{ lang(' to show missing translations.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="px-5 py-0 tabs-div">
                    @foreach ($groups as $group)
                        <a class="btn btn-primary me-1 mt-1 @if ($active == $group->group_langname) active @endif"
                            href="{{ $url ?? route('admin.languages.translate.group', [$language->languagecode, str_replace(' ', '-', $group->group_langname)]) }}">{{ ucfirst($group->group_langname) }}</a>
                    @endforeach

                </div>
                <form action="{{ route('admin.languages.translates.update', $language->id) }}" method="POST">
                    @csrf
                    <div class="p-5 border-xl-top">
                        @forelse($translates as $translate)
                            <div class="d-flex translation-area">
                                <div class="flex-grow-1">
                                    <textarea class="form-control tranlation-height" aria-label="Zone de texte" rows="1" readonly>{{ $translate->key }}</textarea>
                                </div>
                                <div class="p-2">
                                    <i class="fe fe-chevron-right text-muted"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <textarea class="form-control tranlation-height" aria-label="Zone de texte" rows="1"
                                        name="values[{{ $translate->id }}]">{{ $translate->value }}</textarea>
                                </div>
                            </div>
                        @empty

                            <div class="alert text-center mb-0">
                                {{ lang('No data found') }}
                            </div>
                        @endforelse

                    </div>
                        <div class="card-footer">
                        <div class="form-group float-end">
                            <button class="btn btn-secondary" type="submit">{{ lang('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection
