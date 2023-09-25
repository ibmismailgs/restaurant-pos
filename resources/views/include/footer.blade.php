<?php
    $generalSettings = App\Models\Ingredients\GeneralSetting::first();
?>

<footer class="footer">
    <div class="w-100 clearfix">
        <span class="text-center text-sm-left d-md-inline-block">
            @if (empty($generalSettings))
            @else
        	    {{ __('©' . date("Y"))}} <a href="{{ isset($generalSettings) ? $generalSettings->website : ''}}" class="text-primary">{{ isset($generalSettings) ? $generalSettings->name : ''}}, </a>All Rights Reserved
            @endif
        </span>
        <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">
            @if (empty($generalSettings))
            @else
                Email: <b class="text-primary">{{ isset($generalSettings) ? $generalSettings->email : ''}}, </b> Phone: <b class="text-primary">{{ isset($generalSettings) ? $generalSettings->phone : ''}}</b>
            @endif
        </span>
    </div>
</footer>
