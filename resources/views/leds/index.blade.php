@extends('dashboard.layouts.main');

@section('container')
    <!-- Container start -->
    <div class="flex justify-center pl-14 py-10 bg-stone-800">
        <div class="z-0 mb-8 bg-stone-700 p-2 border rounded-md">
            <div class="flex justify-center w-full">
                <div class="w-[1200px]">
                    <div class="flex border-b">
                        <!-- Title start -->
                        <h1 class="index-h1"> Daftar Produk LED</h1>
                        <!-- Title end -->
                        <!-- Button Add start -->
                        @canany(['isAdmin', 'isMedia'])
                            @can('isMediaSetting')
                                @can('isMediaCreate')
                                    <div class="flex">
                                        <a href="/media/leds/create" class="index-link btn-primary">
                                            <svg class="fill-current w-5" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round"
                                                stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="m12.002 2c5.518 0 9.998 4.48 9.998 9.998 0 5.517-4.48 9.997-9.998 9.997-5.517 0-9.997-4.48-9.997-9.997 0-5.518 4.48-9.998 9.997-9.998zm0 1.5c-4.69 0-8.497 3.808-8.497 8.498s3.807 8.497 8.497 8.497 8.498-3.807 8.498-8.497-3.808-8.498-8.498-8.498zm-.747 7.75h-3.5c-.414 0-.75.336-.75.75s.336.75.75.75h3.5v3.5c0 .414.336.75.75.75s.75-.336.75-.75v-3.5h3.5c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-3.5v-3.5c0-.414-.336-.75-.75-.75s-.75.336-.75.75z"
                                                    fill-rule="nonzero" />
                                            </svg>
                                            <span class="mx-1 flex">Tambah Produk LED</span>
                                        </a>
                                    </div>
                                @endcan
                            @endcan
                        @endcanany
                        <!-- Button Add end -->
                    </div>
                    <!-- Form search start -->
                    <form class="flex mt-2" action="/media/leds/">
                        <div class="flex">
                            <input id="search" name="search"
                                class="flex border rounded-l-lg ml-2 p-1 outline-none text-base text-stone-900"
                                type="text" placeholder="Search" value="{{ request('search') }}" onkeyup="submit()"
                                onfocus="this.setSelectionRange(this.value.length, this.value.length);" autofocus>
                            <button class="flex border p-1 rounded-r-lg text-slate-700 justify-center w-10 bg-slate-50"
                                type="submit">
                                <svg class="fill-current w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                    <!-- Form search end -->
                    <!-- Alert start -->
                    @if (session()->has('success'))
                        <div class="ml-2 flex alert-success">
                            <svg class="fill-current w-4 mx-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.25 16.518l-4.5-4.319 1.396-1.435 3.078 2.937 6.105-6.218 1.421 1.409-7.5 7.626z" />
                            </svg>
                            <span class="font-semibold mx-1">Success!</span> {{ session('success') }}
                        </div>
                    @endif
                    @error('delete')
                        <div class="ml-2 flex alert-warning">
                            <svg class="fill-current w-4 mx-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.25 16.518l-4.5-4.319 1.396-1.435 3.078 2.937 6.105-6.218 1.421 1.409-7.5 7.626z" />
                            </svg>
                            <span class="font-semibold mx-1">Warning!!</span> {{ $message }}
                        </div>
                    @enderror
                    <!-- Alert end -->
                </div>
            </div>
            <!-- View start -->
            <div class="flex justify-center w-full mt-2">
                <div class="w-[1200px]">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-stone-400">
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-10" rowspan="2">
                                    No.</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-20" rowspan="2">
                                    <button class="flex justify-center items-center w-20">@sortablelink('code', 'Kode')
                                        <svg class="fill-current w-3 ml-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path d="M12 0l8 10h-16l8-10zm8 14h-16l8 10 8-10z" />
                                        </svg>
                                    </button>
                                </th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-40" rowspan="2">
                                    <button class="flex justify-center items-center w-40">@sortablelink('name', 'Nama Produk')
                                        <svg class="fill-current w-3 ml-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path d="M12 0l8 10h-16l8-10zm8 14h-16l8 10 8-10z" />
                                        </svg>
                                    </button>
                                </th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center" rowspan="2">Nama
                                    Vendor</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-24" rowspan="2">
                                    <button class="flex justify-center items-center w-24">@sortablelink('pixel_pitch', 'Pixel')
                                        <svg class="fill-current w-3 ml-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path d="M12 0l8 10h-16l8-10zm8 14h-16l8 10 8-10z" />
                                        </svg>
                                    </button>
                                </th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center" colspan="4">
                                    Kabinet</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-24" rowspan="2">
                                    Brigthness (ccd/m)</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-24" rowspan="2">
                                    Refresh Rate (Hz)</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-36" rowspan="2">
                                    Action</th>
                            </tr>
                            <tr class="bg-stone-400">
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-20">W ( mm )</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-20">H ( mm )</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-20">Material</th>
                                <th class="text-stone-900 border border-stone-900 text-sm text-center w-20">Berat (kg)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-stone-300">
                            @php
                                $number = 1 + ($leds->currentPage() - 1) * $leds->perPage();
                            @endphp
                            @foreach ($leds as $led)
                                <tr>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $number++ }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->code }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->name }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->vendor->company }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        P{{ $led->pixel_pitch }}-{{ $led->type }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->cabinet_width }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->cabinet_height }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->cabinet_material }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->cabinet_weight }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->brightness }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        {{ $led->refresh_rate }}</td>
                                    <td class="text-stone-900 border border-stone-900 text-sm text-center">
                                        <div class="flex justify-center items-center">
                                            <a href="/media/leds/{{ $led->id }}"
                                                class="index-link text-white w-8 h-5 rounded bg-teal-500 hover:bg-teal-600 drop-shadow-md mr-1">
                                                <svg class="fill-current w-5" clip-rule="evenodd" fill-rule="evenodd"
                                                    stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="m11.998 5c-4.078 0-7.742 3.093-9.853 6.483-.096.159-.145.338-.145.517s.048.358.144.517c2.112 3.39 5.776 6.483 9.854 6.483 4.143 0 7.796-3.09 9.864-6.493.092-.156.138-.332.138-.507s-.046-.351-.138-.507c-2.068-3.403-5.721-6.493-9.864-6.493zm8.413 7c-1.837 2.878-4.897 5.5-8.413 5.5-3.465 0-6.532-2.632-8.404-5.5 1.871-2.868 4.939-5.5 8.404-5.5 3.518 0 6.579 2.624 8.413 5.5zm-8.411-4c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4zm0 1.5c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5z"
                                                        fill-rule="nonzero" />
                                                </svg>
                                            </a>
                                            @canany(['isAdmin', 'isMedia'])
                                                @can('isMediaSetting')
                                                    @can('isMediaEdit')
                                                        <a href="/media/leds/{{ $led->id }}/edit"
                                                            class="index-link text-white w-8 h-5 rounded bg-amber-400 hover:bg-amber-500 drop-shadow-md">
                                                            <svg class="fill-current w-5" clip-rule="evenodd" fill-rule="evenodd"
                                                                stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="m11.25 6c.398 0 .75.352.75.75 0 .414-.336.75-.75.75-1.505 0-7.75 0-7.75 0v12h17v-8.749c0-.414.336-.75.75-.75s.75.336.75.75v9.249c0 .621-.522 1-1 1h-18c-.48 0-1-.379-1-1v-13c0-.481.38-1 1-1zm1.521 9.689 9.012-9.012c.133-.133.217-.329.217-.532 0-.179-.065-.363-.218-.515l-2.423-2.415c-.143-.143-.333-.215-.522-.215s-.378.072-.523.215l-9.027 8.996c-.442 1.371-1.158 3.586-1.264 3.952-.126.433.198.834.572.834.41 0 .696-.099 4.176-1.308zm-2.258-2.392 1.17 1.171c-.704.232-1.274.418-1.729.566zm.968-1.154 7.356-7.331 1.347 1.342-7.346 7.347z"
                                                                    fill-rule="nonzero" />
                                                            </svg>
                                                        </a>
                                                    @endcan
                                                @endcan
                                            @endcanany
                                            @canany(['isAdmin', 'isMedia'])
                                                @can('isMediaSetting')
                                                    @can('isMediaDelete')
                                                        <form action="/media/leds/{{ $led->id }}" method="post"
                                                            class="d-inline m-1">
                                                            @method('delete')
                                                            @csrf
                                                            <button
                                                                class="index-link text-white w-8 h-5 rounded bg-red-600 hover:bg-red-700 drop-shadow-md mr-1"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus produk LED dengan nama {{ $led->name }} ?')">
                                                                <svg class="fill-current w-5" clip-rule="evenodd" fill-rule="evenodd"
                                                                    stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"
                                                                        fill-rule="nonzero" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endcan
                                            @endcanany
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- View end -->
            <!-- Pagination start-->
            <div class="flex justify-center text-stone-100">
                {!! $leds->appends(Request::query())->render('dashboard.layouts.pagination') !!}
            </div>
            <!-- Pagination end -->
        </div>
    </div>
    <!-- Container end -->
@endsection
