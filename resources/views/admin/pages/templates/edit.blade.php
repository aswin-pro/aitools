@extends('admin.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Update Template') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">

            {{-- Failed --}}
            @if (Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <div class="row row-deck row-cards">
                {{-- Update Template --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.update.template') }}" method="post" class="card">
                        @csrf
                        <div class="card-header">
                            <h4 class="page-title">{{ __('Template Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">

                                        <input type="hidden" class="form-control text-capitalize" name="template_id"
                                                    value="{{ $template_details[0]->id }}">

                                        {{-- Categories --}}
                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Categories')
                                                    }}</label>
                                                <select class="form-control form-select" name="category_id"
                                                    id="category_id" required>
                                                    <option disabled="disabled" value="">{{ __('Choose Category')}}
                                                    </option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $template_details[0]->
                                                        category_id == $category->id ? 'selected' : '' }}>{{
                                                        __($category->category_name)
                                                        }}</option>>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Template Name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Template Name') }}</label>
                                                <input type="text" class="form-control text-capitalize" name="name"
                                                    value="{{ $template_details[0]->name }}"
                                                    placeholder="{{ __('Template Name') }}" required>
                                            </div>
                                        </div>

                                        {{-- Template Description --}}
                                        <div class="col-md-12 col-xl-12 mb-3">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Template Description')
                                                    }}</label>
                                                <textarea class="form-control" name="description" id="description"
                                                    rows="2" required>{{ $template_details[0]->description }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Template Fields --}}
                                        <div class="col">
                                            <h4 class="page-title mb-3">
                                                {{ __('Template Fields') }}
                                            </h4>
                                        </div>
                                        <div class="col-auto ms-auto d-print-none">
                                            {{-- Add --}}
                                            <button type="button" onclick="addField()"
                                                class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-circle-plus" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                    </path>
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0">
                                                    </path>
                                                    <path d="M9 12l6 0"></path>
                                                    <path d="M12 9l0 6"></path>
                                                </svg>
                                                {{ __('Add New Field') }}
                                            </button>
                                        </div>

                                        {{-- Fields --}}
                                        <div id="fields" class="mt-3">
                                            @foreach ($template_details as $key => $item)
                                            <div class="row mb-3" id="{{ $key }}">
                                                {{-- AI Input --}}
                                                <div class='col-md-6 col-xl-3'>
                                                    <div class='mb-3'>
                                                        <label class="form-label required">{{ __("AI Input") }}</label>
                                                        <input type="text" class="form-control" name="aiInput[]"
                                                            value="{{ $item->ai_input }}"
                                                            placeholder="{{ __('AI Input') }}" required readonly>
                                                        <small>{{ __("You can't change this value.")}}</small>
                                                    </div>
                                                </div>

                                                {{-- Field Type --}}
                                                <div class="col-md-6 col-xl-3">
                                                    <div class="mb-3">
                                                        <label class='form-label required' for='fieldType'>{{
                                                            __('Field Type')
                                                            }}</label>
                                                        <select name='fieldType[]' id='fieldType' class='form-control'
                                                            required>
                                                            <option value='text' {{ $item->field_type == 'text' ?
                                                                'selected' : '' }}>{{ __('Input Text') }}</option>
                                                            <option value='textarea' {{ $item->field_type == 'textarea'
                                                                ? 'selected' : '' }}>{{ __('Textarea') }}</option>
                                                            <option value='url' {{ $item->field_type == 'url'
                                                                ? 'selected' : '' }}>{{ __('URL') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Field Field --}}
                                                <div class='col-md-6 col-xl-3'>
                                                    <div class='mb-3'>
                                                        <label class="form-label required">{{ __("Field Title")
                                                            }}</label>
                                                        <input type="text" class="form-control" name="fieldTitle[]"
                                                            value="{{ $item->field_name }}"
                                                            placeholder="{{ __('Field Title') }}" required>

                                                    </div>
                                                </div>

                                                {{-- Field Description --}}
                                                <div class='col-md-6 col-xl-3'>
                                                    <div class='mb-2'>
                                                        <label class='form-label'>{{ __('Field Description') }}</label>
                                                        <input type='text' class='form-control'
                                                            name='fieldDescription[]'
                                                            value="{{ $item->field_description }}" placeholder='{{ __('Field Description') }}' required>
                                                    </div>
                                                    {{-- Remove --}}
                                                    <a class='btn btn-danger btn-sm pointer'
                                                        onclick='removeField({{ $key }})'>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-trash" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M4 7l16 0"></path>
                                                            <path d="M10 11l0 6"></path>
                                                            <path d="M14 11l0 6"></path>
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                            </path>
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        {{-- Prompt --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Prompt') }}</label>
                                                <textarea class="form-control" name="prompt" id="prompt" rows="5"
                                                    placeholder="{{ __('Eg: Write ##results## ##tone## website landing page heading and description in ##lang## based on ##input1##') }}"
                                                    required>{{ $template_details[0]->prompt }}</textarea>
                                                <small class="form-hint">
                                                    {{ __('##results## - No. Of Results by the user') }} <br>
                                                    {{ __('##tone## - Tone by the user') }} <br>
                                                    {{ __('##lang## - User selectable language') }} <br>
                                                    {{ __('##input## - These are the words that the user has to enter') }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                    {{ __('Update') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.includes.footer')
</div>

{{-- Custom JS --}}
@section('custom-js')
<script src="{{ asset('js/tom-select.base.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        "use strict";
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('category_id'), {
    		copyClassesToDropdown: false,
    		dropdownClass: 'dropdown-menu ts-dropdown',
    		optionClass:'dropdown-item',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
</script>
<script>
    var count = {{ count($template_details) }};
function addField() {
	"use strict";
    count++;
    var id = getRandomInt();
    var fields = `<div class="row mb-3" id=`+ id +`>
                    <div class='col-md-6 col-xl-3'>
                        <div class='mb-3'>
                            <label class="form-label required">{{ __("AI Input") }}</label>
                            <input type="text" class="form-control" name="aiInput[]"
                                value="##input`+count+`##"
                                placeholder="{{ __('AI Input') }}" required readonly>
                            <small>{{ __("You can't change this value.")}}</small>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="mb-3">
                            <label class='form-label required' for='fieldType'>{{ __('Field Type') }}</label>
                            <select name='fieldType[]' id='fieldType' class='form-control' required>
                                <option value='text'>{{ __('Input Text') }}</option>
                                <option value='textarea'>{{ __('Textarea') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class='col-md-6 col-xl-3'>
                        <div class='mb-3'> 
                            <label class="form-label required">{{ __("Field Title") }}</label>
                            <input type="text" class="form-control" name="fieldTitle[]" placeholder="{{ __('Field Title') }}" required>
                        </div>
                    </div>

                    <div class='col-md-6 col-xl-3'>
                        <div class='mb-2'>
                            <label class='form-label'>{{ __('Field Description') }}</label>
                            <input type='text' class='form-control' name='fieldDescription[]' placeholder='{{ __('Field Description') }}' required>
                        </div>
                        <a class='btn btn-danger btn-sm pointer' onclick='removeField(`+id+`)'>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-trash" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                </path>
                                <path d="M4 7l16 0"></path>
                                <path d="M10 11l0 6"></path>
                                <path d="M14 11l0 6"></path>
                                <path
                                    d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                </path>
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>`;
    $("#fields").append(fields).html();
}

function removeField(id) {
    if (id != 0) {
        "use strict";
    $("#"+id).remove();
    }
}

function getRandomInt() {
    min = Math.ceil(0);
    max = Math.floor(9999999999);
    return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
}
</script>
@endsection
@endsection