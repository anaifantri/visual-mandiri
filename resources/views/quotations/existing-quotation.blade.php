<table class="table-auto w-full">
    <thead>
        <tr class="bg-stone-400">
            <th class="text-stone-900 border border-stone-900 text-xs w-8 text-center" rowspan="2">No</th>
            <th class="text-stone-900 border border-stone-900 text-xs w-20 text-center" rowspan="2">Kode</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center" rowspan="2">Lokasi</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-16" rowspan="2">Area</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-16" rowspan="2">Kota</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-28" rowspan="2">Klien</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-44" colspan="2">Periode Kontrak
            </th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-48" colspan="4">Deskripsi Lokasi
            </th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-14" rowspan="2">Action</th>
        </tr>
        <tr class="bg-stone-400">
            <th class="text-stone-900 border border-stone-900 text-xs w-24 text-center" rowspan="2">Awal</th>
            <th class="text-stone-900 border border-stone-900 text-xs w-24 text-center" rowspan="2">Akhir</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-10" rowspan="2">Jenis</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-10" rowspan="2">BL/FL</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-8" rowspan="2">Side</th>
            <th class="text-stone-900 border border-stone-900 text-xs text-center w-20" rowspan="2">Size - V/H</th>
        </tr>
    </thead>
    <tbody class="bg-stone-300">
        @foreach ($locations as $location)
            @php
                $description = json_decode($location->description);
            @endphp
            <tr>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">{{ $loop->iteration }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">
                    {{ $location->code }}
                    -
                    {{ $location->city_code }}</td>
                <td class="text-stone-900 border border-stone-900 text-xs px-2">{{ $location->address }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">
                    {{ $location->area }}</td>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">
                    {{ $location->city }}</td>
                <td class="text-stone-900 border border-stone-900 text-xs px-2 text-center">
                    {{ $clients[$loop->iteration - 1]->name }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs px-2 text-center">
                    {{ date('d-M-Y', strtotime($sales[$loop->iteration - 1]->start_at)) }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs px-2 text-center">
                    {{ date('d-M-Y', strtotime($sales[$loop->iteration - 1]->end_at)) }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-sm text-center">
                    @if ($location->category == 'Billboard')
                        BB
                    @elseif($location->category == 'Videotron')
                        VT
                    @elseif($location->category == 'Signage')
                        SN
                    @elseif($location->category == 'Bando')
                        BD
                    @elseif($location->category == 'Baliho')
                        BLH
                    @elseif($location->category == 'Midiboard')
                        MB
                    @endif
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">
                    @if ($location->category == 'Videotron')
                        -
                    @elseif ($location->category == 'Signage')
                        @if ($description->type == 'Videotron')
                            -
                        @else
                            @if ($description->lighting == 'Backlight')
                                BL
                            @elseif ($description->lighting == 'Frontlight')
                                FL
                            @elseif ($description->lighting == 'Nonlight')
                                NL
                            @endif
                        @endif
                    @else
                        @if ($description->lighting == 'Backlight')
                            BL
                        @elseif ($description->lighting == 'Frontlight')
                            FL
                        @elseif ($description->lighting == 'Nonlight')
                            NL
                        @endif
                    @endif
                </td>
                <td class="text-stone-900 border border-stone-900 text-sm text-center">
                    {{ filter_var($location->side, FILTER_SANITIZE_NUMBER_INT) }}
                </td>
                <td class="text-stone-900 border border-stone-900 text-xs text-center">
                    {{ $location->size }}
                    -
                    @if ($location->orientation == 'Vertikal')
                        V
                    @elseif ($location->orientation == 'Horizontal')
                        H
                    @endif
                </td>
                <td class="text-stone-900 border border-stone-900 text-center text-xs">
                    <input id="{{ $clients[$loop->iteration - 1]->name }}"
                        value="{{ $sales[$loop->iteration - 1]->id }}" type="checkbox" title="pilih"
                        onclick="getExistingLocation(this)">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>