@php
    if ($description->type == 'Videotron') {
        foreach ($leds as $led) {
            if ($led->id == $description->led_id) {
                $dataLed = $led;
            }
        }
    }
@endphp
<div class="w-[256px] h-[170px] bg-slate-50 mt-1 rounded-b-lg border">
    <div class="flex mt-1">
        <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Jenis</span>
        <span class="w-[150px] text-xs font-sans font-bold tracking-wide text-teal-900">:
            {{ $location->media_category->name }} - {{ $description->type }}</span>
    </div>
    <div class="flex mt-1">
        <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Ukuran</span>
        <span class="w-[120px] text-xs font-sans font-bold tracking-wide text-teal-900">:
            {{ $location->media_size->size }} - {{ $location->side }}</span>
    </div>
    <div class="flex mt-1">
        <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Jumlah</span>
        <span class="w-[120px] text-xs font-sans font-bold tracking-wide text-teal-900">:
            {{ $description->qty }} unit</span>
    </div>
    @if ($description->type == 'Videotron')
        <div class="flex mt-1">
            <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Screen Size</span>
            @if ($description->screen_w < $description->screen_h)
                <span class="w-[140px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                    {{ $description->screen_w }} pixel x {{ $description->screen_h }} pixel</span>
            @else
                <span class="w-[140px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                    {{ $description->screen_h }} pixel x {{ $description->screen_w }} pixel</span>
            @endif
        </div>
    @endif
    <div class="flex mt-1">
        <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Orientasi</span>
        <span class="w-[120px] text-xs font-sans font-bold tracking-wide text-teal-900">:
            {{ $location->orientation }}</span>
    </div>
    @if ($description->type != 'Videotron')
        <div class="flex mt-1">
            <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Penerangan</span>
            <span class="w-[120px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                {{ $description->lighting }}
            </span>
        </div>
    @endif
    @if ($description->type == 'Videotron')
        <div class="flex mt-1">
            <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Type LED</span>
            <span class="w-[140px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                {{ $dataLed->pixel_pitch }} - {{ $dataLed->type }} - {{ $dataLed->pixel_config }}
            </span>
        </div>
        <div class="flex mt-1">
            <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Pixel Density</span>
            <span class="w-[140px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                {{ $dataLed->pixel_density }}
            </span>
        </div>
        <div class="flex mt-1">
            <span class="w-[90px] text-xs font-sans font-bold tracking-wide text-teal-900 ml-2">Refresh Rate</span>
            <span class="w-[140px] text-xs font-sans font-bold tracking-wide text-teal-900">:
                {{ $dataLed->refresh_rate }}
            </span>
        </div>
    @endif
</div>