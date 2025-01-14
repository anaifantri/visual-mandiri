@php
    $cbTitle = 0;
    foreach ($price->dataTitle as $dataTitle) {
        if ($dataTitle->checkbox == true) {
            $cbTitle = $cbTitle + 1;
        }
    }
    if ($cbTitle > 2) {
        $width = 850;
    } else {
        $width = 725;
    }
@endphp
<div class="w-[{{ $width }}px]">
    <table class="table-auto mt-2 w-full">
        <thead>
            <tr>
                <th class="text-[0.7rem] text-black border w-6" rowspan="2">No
                </th>
                <th class="text-[0.7rem] text-black border w-[72px]" rowspan="2">
                    Kode
                </th>
                <th class="text-[0.7rem] text-black border" rowspan="2">Lokasi
                </th>
                @if ($category == 'Signage')
                    <th class="text-[0.7rem] text-black border" colspan="4">Deskripsi</th>
                @else
                    <th class="text-[0.7rem] text-black border" colspan="3">Deskripsi</th>
                @endif
                <th class="text-[0.7rem] text-black border" colspan="{{ $cbTitle }}">Harga
                    (Rp.)
                </th>
            </tr>
            <tr>
                @if ($category == 'Signage')
                    <th class="text-[0.7rem] text-black border w-16" rowspan="2">Bentuk</th>
                @else
                    <th class="text-[0.7rem] text-black border w-10" rowspan="2">BL/FL</th>
                @endif
                @if ($category == 'Signage')
                    <th class="text-[0.7rem] text-black border w-6" rowspan="2">Qty</th>
                @endif
                <th class="text-[0.7rem] text-black border w-8" rowspan="2">Side</th>
                <th class="text-[0.7rem] text-black border w-20" rowspan="2">Size - V/H
                </th>
                @foreach ($price->dataTitle as $title)
                    @if ($title->checkbox == true)
                        <th class="text-[0.7rem] text-black border w-[85px]">
                            {{ $title->title }}</th>
                    @else
                        <th class="text-[0.7rem] text-black border w-[85px]" hidden>
                            {{ $title->title }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody id="previewTBody">
            @foreach ($products as $product)
                <?php
                $row = $loop->iteration - 1;
                $description = json_decode($product->description);
                ?>
                <tr>
                    <td class="text-[0.7rem] text-black border text-center">
                        {{ $loop->iteration }}</td>
                    <td class="text-[0.7rem] text-black border text-center">
                        {{ $product->code }} - {{ $product->city_code }}</td>
                    <td class="text-[0.7rem] text-black border px-2">
                        {{ $product->address }}
                    </td>
                    @if ($category == 'Signage')
                        <td class="text-[0.7rem] text-black border text-center">{{ $description->type }}</td>
                    @else
                        <td class="text-[0.7rem] text-black border text-center">
                            @if ($description->lighting == 'Backlight')
                                BL
                            @elseif ($description->lighting == 'Frontlight')
                                FL
                            @endif
                        </td>
                    @endif
                    @if ($category == 'Signage')
                        <td class="text-[0.7rem] text-black border text-center">
                            {{ $description->qty }}
                        </td>
                    @endif
                    <td class="text-[0.7rem] text-black border text-center">
                        {{ (int) filter_var($product->side, FILTER_SANITIZE_NUMBER_INT) }}
                    </td>
                    <td class="text-[0.7rem] text-black border text-center">
                        {{ $product->size }} -
                        @if ($product->orientation == 'Vertikal')
                            V
                        @elseif ($product->orientation == 'Horizontal')
                            H
                        @endif
                    </td>
                    @foreach ($price->dataPrice as $priceValue)
                        @if ($price->dataTitle[$loop->iteration - 1]->checkbox == true)
                            <td class="text-[0.7rem] text-black border text-right px-1">
                                {{ number_format($priceValue[$row]->price) }}</td>
                        @else
                            <td class="text-[0.7rem] text-black border text-right px-1" hidden>
                                {{ number_format($priceValue[$row]->price) }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            @if ($price->objPpn->checked == true)
                @php
                    $subTotal = 0;
                @endphp
                @foreach ($products as $product)
                    @php
                        $row = $loop->iteration - 1;
                    @endphp
                    @foreach ($price->dataPrice as $ppnPrice)
                        @if ($price->dataTitle[$loop->iteration - 1]->checkbox == true)
                            @php
                                $subTotal = $subTotal + $ppnPrice[$row]->price;
                            @endphp
                        @endif
                    @endforeach
                @endforeach
                <tr>
                    @if ($category == 'Signage')
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="7">Sub
                            Total (A)</td>
                    @else
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="6">Sub
                            Total (A)</td>
                    @endif
                    <td class="text-[0.7rem] text-black border font-semibold px-1 text-right">
                        {{ number_format($subTotal) }}
                    </td>
                </tr>
                @if ($price->objPpn->dpp != $subTotal)
                    <tr>
                        @if ($category == 'Signage')
                            <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="7">DPP
                            </td>
                        @else
                            <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="6">DPP
                            </td>
                        @endif
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right">
                            {{ number_format($price->objPpn->dpp) }}</td>
                    </tr>
                @endif
                <tr>
                    @if ($category == 'Signage')
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="7">PPN
                            {{ $price->objPpn->value }} % (B)
                        </td>
                    @else
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="6">PPN
                            {{ $price->objPpn->value }} % (B)
                        </td>
                    @endif
                    <td class="text-[0.7rem] text-black border font-semibold px-1 text-right">
                        {{ number_format($price->objPpn->dpp * ($price->objPpn->value / 100)) }}</td>
                </tr>
                <tr>
                    @if ($category == 'Signage')
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="7">
                            Grand Total (A + B)</td>
                    @else
                        <td class="text-[0.7rem] text-black border font-semibold px-1 text-right" colspan="6">
                            Grand Total (A + B)</td>
                    @endif
                    <td class="text-[0.7rem] text-black border font-semibold px-1 text-right">
                        @if ($price->objPpn->dpp != $subTotal)
                            {{ number_format($subTotal + $price->objPpn->dpp * ($price->objPpn->value / 100)) }}
                        @else
                            {{ number_format($subTotal + $subTotal * ($price->objPpn->value / 100)) }}
                        @endif
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
