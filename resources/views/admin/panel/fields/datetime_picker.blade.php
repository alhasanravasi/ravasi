{{-- bootstrap datetimepicker input --}}

<?php
    // if the column has been cast to Carbon or Date (using attribute casting)
    // get the value as a date string
    if (isset($field['value']) && ( $field['value'] instanceof \Carbon\Carbon )) {
        $field['value'] = $field['value']->format( 'Y-m-d H:i:s' );
    }

    $field_language = isset($field['datetime_picker_options']['language'])?$field['datetime_picker_options']['language']:\App::getLocale();
?>

<div @include('admin.panel.inc.field_wrapper_attributes') >
    <input type="hidden" name="{{ $field['name'] }}" value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}">
    <label class="form-label fw-bolder">
        {!! $field['label'] !!}
        @if (isset($field['required']) && $field['required'])
            <span class="text-danger">*</span>
        @endif
    </label>
    @include('admin.panel.fields.inc.translatable_icon')
    <div class="input-group date">
        <input
            data-bs-datetimepicker="{{ isset($field['datetime_picker_options']) ? json_encode($field['datetime_picker_options']) : '{}'}}"
            type="text"
            @include('admin.panel.inc.field_attributes')
            >
        <span class="input-group-text">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <div class="form-text">{!! $field['hint'] !!}</div>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($xPanel->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/1.9.0/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datetimepicker/4.17.42/css/bootstrap-datetimepicker.min.css') }}" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script src="{{ asset('assets/plugins/datepicker/1.9.0/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/momentjs/2.10.3/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-datetimepicker/4.17.42/js/bootstrap-datetimepicker.min.js') }}"></script>
    @if ($field_language !== 'en')
        <script charset="UTF-8" src="{{ asset('assets/plugins/datepicker/1.9.0/locales/bootstrap-datepicker.'.$field_language.'.js') }}"></script>
        <script charset="UTF-8" src="{{ asset('assets/plugins/momentjs/2.30.1/locale/'.$field_language.'.min.js') }}"></script>
    @endif
    <script>
        var dateFormat=function(){var a=/d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,b=/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,c=/[^-+\dA-Z]/g,d=function(a,b){for(a=String(a),b=b||2;a.length<b;)a="0"+a;return a};return function(e,f,g){var h=dateFormat;if(1!=arguments.length||"[object String]"!=Object.prototype.toString.call(e)||/\d/.test(e)||(f=e,e=void 0),e=e?new Date(e):new Date,isNaN(e))throw SyntaxError("invalid date");f=String(h.masks[f]||f||h.masks.default),"UTC:"==f.slice(0,4)&&(f=f.slice(4),g=!0);var i=g?"getUTC":"get",j=e[i+"Date"](),k=e[i+"Day"](),l=e[i+"Month"](),m=e[i+"FullYear"](),n=e[i+"Hours"](),o=e[i+"Minutes"](),p=e[i+"Seconds"](),q=e[i+"Milliseconds"](),r=g?0:e.getTimezoneOffset(),s={d:j,dd:d(j),ddd:h.i18n.dayNames[k],dddd:h.i18n.dayNames[k+7],m:l+1,mm:d(l+1),mmm:h.i18n.monthNames[l],mmmm:h.i18n.monthNames[l+12],yy:String(m).slice(2),yyyy:m,h:n%12||12,hh:d(n%12||12),H:n,HH:d(n),M:o,MM:d(o),s:p,ss:d(p),l:d(q,3),L:d(q>99?Math.round(q/10):q),t:n<12?"a":"p",tt:n<12?"am":"pm",T:n<12?"A":"P",TT:n<12?"AM":"PM",Z:g?"UTC":(String(e).match(b)||[""]).pop().replace(c,""),o:(r>0?"-":"+")+d(100*Math.floor(Math.abs(r)/60)+Math.abs(r)%60,4),S:["th","st","nd","rd"][j%10>3?0:(j%100-j%10!=10)*j%10]};return f.replace(a,function(a){return a in s?s[a]:a.slice(1,a.length-1)})}}();dateFormat.masks={default:"ddd mmm dd yyyy HH:MM:ss",shortDate:"m/d/yy",mediumDate:"mmm d, yyyy",longDate:"mmmm d, yyyy",fullDate:"dddd, mmmm d, yyyy",shortTime:"h:MM TT",mediumTime:"h:MM:ss TT",longTime:"h:MM:ss TT Z",isoDate:"yyyy-mm-dd",isoTime:"HH:MM:ss",isoDateTime:"yyyy-mm-dd'T'HH:MM:ss",isoUtcDateTime:"UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"},dateFormat.i18n={dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],monthNames:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","January","February","March","April","May","June","July","August","September","October","November","December"]},Date.prototype.format=function(a,b){return dateFormat(this,a,b)};

        onDocumentReady((event) => {
            $('[data-bs-datetimepicker]').each(function(){

                var $fake = $(this),
                $field = $fake.parents('.form-group').find('input[type="hidden"]'),
                $customConfig = $.extend({
                    format: 'DD/MM/YYYY HH:mm',
                    defaultDate: $field.val()
                }, $fake.data('bs-datetimepicker'));

                $customConfig.locale = $customConfig['language'];
                delete($customConfig['language']);
                $picker = $fake.datetimepicker($customConfig);

                $fake.on('keydown', function(e){
                    e.preventDefault();
                    return false;
                });

                $picker.on('dp.change', function(e){
                    var sqlDate = e.date.format('YYYY-MM-DD HH:mm:ss');
                    $field.val(sqlDate);
                });

            });
        });
    </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
