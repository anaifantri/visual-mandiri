@extends('dashboard.layouts.main');

@section('container')
    <form method="post" action="/marketing/printing-products/{{ $printing_product->id }}" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="flex justify-center pl-14 py-10 bg-stone-800">
            <div class="w-[350px] h-[500px] border rounded-lg p-4 bg-stone-300">
                <div class="flex items-center justify-center mb-2 border-b w-full">
                    <h4 class="text-xl font-semibold tracking-wider text-stone-900">Edit Data Bahan Cetak</h4>
                </div>
                <div>
                    <div class="flex justify-center mt-4 w-full">
                        <div class="mt-1">
                            <label class="text-sm text-stone-900">Kode</label>
                            <input
                                class="flex px-2 text-sm font-semibold text-slate-400 w-[250px] border rounded-lg p-1 outline-none @error('code') is-invalid @enderror"
                                type="text" id="code" name="code" value="{{ $printing_product->code }}" readonly
                                title="Terisi otomatis" required>
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-center mt-4 w-full">
                        <div class="mt-1">
                            <label class="text-sm text-stone-900">Nama Bahan</label>
                            <input
                                class="flex px-2 text-sm font-semibold text-stone-900 w-[250px] border rounded-lg p-1 outline-none @error('name') is-invalid @enderror"
                                type="text" id="name" name="name" placeholder="Input nama bahan"
                                value="{{ $printing_product->name }}" autofocus required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-center w-full mt-2">
                        <div class="mt-1">
                            @php
                                $types = ['Frontlight', 'Backlight'];
                            @endphp
                            <div class="flex mt-1">
                                <label class="text-sm text-stone-900">Type</label>
                            </div>
                            <div class="mt-1">
                                <select id="type" name="type"
                                    class="w-[250px] text-sm font-semibold text-stone-900 border rounded-lg p-1 outline-none
                                            @error('type') is-invalid @enderror"
                                    type="text" value="{{ $printing_product->type }}">
                                    @foreach ($types as $type)
                                        @if ($printing_product->type == $type)
                                            <option value="{{ $type }}" selected>
                                                {{ $type }}
                                            </option>
                                        @else
                                            <option value="{{ $type }}">
                                                {{ $type }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center w-full mt-2">
                        <div class="mt-1">
                            <label class="text-sm text-stone-900">Deskripsi</label>
                            <textarea
                                class="flex text-sm font-semibold text-stone-900 w-[250px]  border rounded-lg p-1 outline-teal-300 @error('address') is-invalid @enderror"
                                name="description" id="description" placeholder="Input deskripsi bahan">{{ $printing_product->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-center w-full">
                        <div class="mt-1">
                            <label class="text-sm text-stone-900">Harga Jual</label>
                            <input
                                class="flex px-2 text-sm font-semibold text-stone-900 w-[250px] in-out-spin-none border rounded-lg p-1 outline-none @error('price') is-invalid @enderror"
                                type="number" min="0" id="price" name="price" placeholder="Input Harga Jual"
                                value="{{ $printing_product->price }}" required>
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-center w-full mt-4">
                        <button class="flex items-center justify-center btn-primary mx-1" type="submit" id="btnSubmit"
                            name="btnSubmit">
                            <svg class="fill-current w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path
                                    d="M15.004 3h2.996v5h-2.996v-5zm8.996 1v20h-24v-24h20l4 4zm-19 5h14v-7h-14v7zm16 4h-18v9h18v-9zm-2 2h-14v1h14v-1zm0 2h-14v1h14v-1zm0 2h-14v1h14v-1z" />
                            </svg>
                            <span class="mx-2"> Update </span>
                        </button>
                        <a href="/marketing/printing-products" class="flex items-center justify-center btn-danger mx-1">
                            <svg class="fill-current w-5" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round"
                                stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"
                                    fill-rule="nonzero" />
                            </svg>
                            <span class="mx-1"> Cancel </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection